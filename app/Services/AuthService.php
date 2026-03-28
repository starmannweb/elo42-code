<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Core\Session;
use App\Models\User;
use App\Support\Logger;

class AuthService
{
    public function register(array $data): array
    {
        try {
            $existing = User::findByEmail($data['email']);
            if ($existing) {
                return ['success' => false, 'error' => 'Este e-mail ja esta cadastrado.'];
            }

            $userId = User::createAccount([
                'name'     => trim($data['first_name'] . ' ' . $data['last_name']),
                'email'    => $data['email'],
                'phone'    => $data['phone'] ?? null,
                'password' => $data['password'],
                'status'   => 'active',
            ]);

            $this->createEmailVerification((int) $userId);

            $user = User::find((int) $userId);
            if (!$user) {
                return ['success' => false, 'error' => 'Nao foi possivel concluir seu cadastro agora.'];
            }

            $this->loginUser($user);
            $this->logAudit((int) $userId, 'user.registered');

            return ['success' => true, 'user_id' => $userId];
        } catch (\Throwable $e) {
            try {
                (new Logger())->error('auth.register_failed', [
                    'email' => $data['email'] ?? null,
                    'error' => $e->getMessage(),
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                ]);
            } catch (\Throwable $logError) {
                // Ignore logger failures.
            }

            return [
                'success' => false,
                'error'   => 'Nao foi possivel concluir seu cadastro agora. Tente novamente em alguns instantes.',
            ];
        }
    }

    public function attempt(string $email, string $password): array
    {
        try {
            $user = User::findByEmail($email);

            if (!$user) {
                return ['success' => false, 'error' => 'E-mail ou senha incorretos.'];
            }

            if ($user['status'] === 'suspended') {
                return ['success' => false, 'error' => 'Sua conta esta suspensa. Entre em contato com o suporte.'];
            }

            if (!User::verifyPassword($password, $user['password'])) {
                return ['success' => false, 'error' => 'E-mail ou senha incorretos.'];
            }

            $this->loginUser($user);
            $this->updateLastLogin((int) $user['id']);
            $this->logAudit((int) $user['id'], 'user.login');

            return ['success' => true, 'user' => $user];
        } catch (\Throwable $e) {
            try {
                (new Logger())->error('auth.attempt_failed', [
                    'email' => $email,
                    'error' => $e->getMessage(),
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                ]);
            } catch (\Throwable $logError) {
                // Ignore logger failures.
            }

            return [
                'success' => false,
                'error'   => 'Nao foi possivel autenticar agora. Tente novamente em alguns instantes.',
            ];
        }
    }

    public function logout(): void
    {
        $user = Session::user();
        if ($user) {
            $this->logAudit((int) $user['id'], 'user.logout');
        }
        Session::destroy();
    }

    public function createPasswordReset(string $email): array
    {
        $user = User::findByEmail($email);

        if (!$user) {
            return ['success' => true, 'message' => 'Se o e-mail estiver cadastrado, enviaremos as instrucoes de recuperacao.'];
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            INSERT INTO password_resets (email, token, expires_at)
            VALUES (:email, :token, :expires_at)
        ");
        $stmt->execute([
            'email'      => $email,
            'token'      => hash('sha256', $token),
            'expires_at' => $expiresAt,
        ]);

        // TODO: Send email with reset link containing $token

        $this->logAudit((int) $user['id'], 'password.reset_requested');

        return ['success' => true, 'message' => 'Se o e-mail estiver cadastrado, enviaremos as instrucoes de recuperacao.'];
    }

    public function resetPassword(string $token, string $password): array
    {
        $pdo = Database::connection();
        $hashedToken = hash('sha256', $token);

        $stmt = $pdo->prepare("
            SELECT * FROM password_resets
            WHERE token = :token AND used = 0 AND expires_at > NOW()
            ORDER BY created_at DESC LIMIT 1
        ");
        $stmt->execute(['token' => $hashedToken]);
        $reset = $stmt->fetch();

        if (!$reset) {
            return ['success' => false, 'error' => 'Token invalido ou expirado. Solicite uma nova recuperacao de senha.'];
        }

        $user = User::findByEmail($reset['email']);
        if (!$user) {
            return ['success' => false, 'error' => 'Usuario nao encontrado.'];
        }

        User::update((int) $user['id'], [
            'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        $stmt = $pdo->prepare("UPDATE password_resets SET used = 1 WHERE id = :id");
        $stmt->execute(['id' => $reset['id']]);

        $this->logAudit((int) $user['id'], 'password.reset_completed');

        return ['success' => true, 'message' => 'Senha alterada com sucesso. Faca login com sua nova senha.'];
    }

    public function verifyEmail(string $token): array
    {
        $pdo = Database::connection();
        $hashedToken = hash('sha256', $token);

        $stmt = $pdo->prepare("
            SELECT * FROM email_verifications
            WHERE token = :token AND verified_at IS NULL AND expires_at > NOW()
            LIMIT 1
        ");
        $stmt->execute(['token' => $hashedToken]);
        $verification = $stmt->fetch();

        if (!$verification) {
            return ['success' => false, 'error' => 'Link de verificacao invalido ou expirado.'];
        }

        $pdo->prepare("UPDATE email_verifications SET verified_at = NOW() WHERE id = :id")
            ->execute(['id' => $verification['id']]);

        User::update((int) $verification['user_id'], [
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        $user = Session::user();
        if ($user && (int) $user['id'] === (int) $verification['user_id']) {
            $user['email_verified_at'] = date('Y-m-d H:i:s');
            Session::set('user', $user);
        }

        return ['success' => true, 'message' => 'E-mail verificado com sucesso!'];
    }

    private function createEmailVerification(int $userId): void
    {
        try {
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

            $pdo = Database::connection();
            $stmt = $pdo->prepare("
                INSERT INTO email_verifications (user_id, token, expires_at)
                VALUES (:user_id, :token, :expires_at)
            ");
            $stmt->execute([
                'user_id'    => $userId,
                'token'      => hash('sha256', $token),
                'expires_at' => $expiresAt,
            ]);

            // TODO: Send verification email with link containing $token
        } catch (\Throwable $e) {
            $this->logNonCritical('auth.email_verification_create_failed', [
                'user_id' => $userId,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    private function loginUser(array $user): void
    {
        $permissions = [];
        $organization = null;

        try {
            $permissions = User::getPermissions((int) $user['id']);
        } catch (\Throwable $e) {
            $this->logNonCritical('auth.permissions_lookup_failed', [
                'user_id' => $user['id'] ?? null,
                'error'   => $e->getMessage(),
            ]);
        }

        try {
            $organization = User::getOrganization((int) $user['id']);
        } catch (\Throwable $e) {
            $this->logNonCritical('auth.organization_lookup_failed', [
                'user_id' => $user['id'] ?? null,
                'error'   => $e->getMessage(),
            ]);
        }

        Session::regenerate();
        Session::set('user', [
            'id'                 => $user['id'],
            'name'               => $user['name'],
            'email'              => $user['email'],
            'phone'              => $user['phone'] ?? null,
            'avatar'             => $user['avatar'] ?? null,
            'status'             => $user['status'],
            'email_verified_at'  => $user['email_verified_at'] ?? null,
            'permissions'        => $permissions,
        ]);

        if ($organization) {
            Session::set('organization', [
                'id'        => $organization['id'],
                'name'      => $organization['name'],
                'slug'      => $organization['slug'],
                'type'      => $organization['type'],
                'plan'      => $organization['plan'],
                'status'    => $organization['status'],
                'role_slug' => $organization['role_slug'] ?? null,
                'role_name' => $organization['role_name'] ?? null,
            ]);
        }
    }

    private function updateLastLogin(int $userId): void
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id");
            $stmt->execute(['id' => $userId]);
        } catch (\Throwable $e) {
            $this->logNonCritical('auth.update_last_login_failed', [
                'user_id' => $userId,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    private function logAudit(int $userId, string $action): void
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("
                INSERT INTO audit_logs (user_id, action, ip_address, user_agent, created_at)
                VALUES (:user_id, :action, :ip, :ua, NOW())
            ");
            $stmt->execute([
                'user_id' => $userId,
                'action'  => $action,
                'ip'      => $_SERVER['REMOTE_ADDR'] ?? null,
                'ua'      => $_SERVER['HTTP_USER_AGENT'] ?? null,
            ]);
        } catch (\Throwable $e) {
            $this->logNonCritical('auth.audit_log_failed', [
                'user_id' => $userId,
                'action'  => $action,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    private function logNonCritical(string $message, array $context = []): void
    {
        try {
            (new Logger())->warning($message, $context);
        } catch (\Throwable $e) {
            // Ignore logger failures.
        }
    }
}
