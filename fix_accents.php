<?php
$dir = __DIR__ . '/resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$entities = [
    '&aacute;' => 'á', '&eacute;' => 'é', '&iacute;' => 'í', '&oacute;' => 'ó', '&uacute;' => 'ú',
    '&atilde;' => 'ã', '&otilde;' => 'õ', '&ccedil;' => 'ç',
    '&Agrave;' => 'À', '&Aacute;' => 'Á', '&Eacute;' => 'É', '&Iacute;' => 'Í', '&Oacute;' => 'Ó', '&Uacute;' => 'Ú',
    '&Atilde;' => 'Ã', '&Otilde;' => 'Õ', '&Ccedil;' => 'Ç',
    '&ecirc;' => 'ê', '&Ecirc;' => 'Ê', '&ocirc;' => 'ô', '&Ocirc;' => 'Ô', '&acirc;' => 'â', '&Acirc;' => 'Â'
];

$words = [
    'orientacao' => 'orientação',
    'Dizimos' => 'Dízimos',
    'dizimos' => 'dízimos',
    'Gestao' => 'Gestão',
    'gestao' => 'gestão',
    'organizacao' => 'organização',
    'Organizacao' => 'Organização',
    'configuracoes' => 'configurações',
    'Configuracoes' => 'Configurações',
    'relatorios' => 'relatórios',
    'Relatorios' => 'Relatórios',
    'sermoes' => 'sermões',
    'Sermoes' => 'Sermões',
    'aniversarios' => 'aniversários',
    'Aniversarios' => 'Aniversários',
    'notificacoes' => 'notificações',
    'Notificacoes' => 'Notificações',
    'integracao' => 'integração',
    'Integracao' => 'Integração',
    'organizacoes' => 'organizações',
    'Organizacoes' => 'Organizações',
    'servicos' => 'serviços',
    'Servicos' => 'Serviços',
    'promocoes' => 'promoções',
    'Promocoes' => 'Promoções',
    'etaria' => 'etária',
    'Etaria' => 'Etária',
    'genero' => 'gênero',
    'Genero' => 'Gênero'
];

$updatedFiles = [];

foreach ($files as $file) {
    if ($file->isDir() || $file->getExtension() !== 'php') continue;
    
    $path = $file->getRealPath();
    $content = file_get_contents($path);
    $original = $content;
    
    // 1. Replace entities
    foreach ($entities as $entity => $utf8) {
        $content = str_replace($entity, $utf8, $content);
    }
    
    // 2. Replace words
    foreach ($words as $word => $accented) {
        // Negative lookbehind: Not preceded by $, ->, /
        // Negative lookahead: Not followed by => (with optional spaces), [, ], ', ", (, )
        // This avoids variables, object properties, URLs, and most array keys/string literals in code.
        $pattern = '/(?<![\$\-\>\/])\b' . preg_quote($word, '/') . '\b(?!\s*=>)(?![\[\]\'\"\(\)])/';
        $content = preg_replace($pattern, $accented, $content);
    }
    
    if ($content !== $original) {
        file_put_contents($path, $content);
        $updatedFiles[] = $path;
    }
}

echo "Total files updated: " . count($updatedFiles) . "\n";
foreach ($updatedFiles as $f) {
    echo "- " . str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $f) . "\n";
}
