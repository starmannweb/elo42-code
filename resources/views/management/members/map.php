<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<?php
    $mapMembers = array_values(array_map(static function (array $member): array {
        $address = array_values(array_filter([
            $member['address'] ?? '',
            $member['city'] ?? '',
            $member['state'] ?? '',
        ], static fn ($value): bool => trim((string) $value) !== ''));

        return [
            'id' => (int) ($member['id'] ?? 0),
            'name' => (string) ($member['name'] ?? ''),
            'email' => (string) ($member['email'] ?? ''),
            'phone' => (string) ($member['phone'] ?? ''),
            'status' => (string) ($member['status'] ?? ''),
            'photo' => (string) ($member['photo'] ?? ''),
            'address' => implode(', ', $address),
            'lat' => (float) ($member['latitude'] ?? 0),
            'lng' => (float) ($member['longitude'] ?? 0),
        ];
    }, is_array($members ?? null) ? $members : []));
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Mapa de Membros</h1>
        <p class="mgmt-header__subtitle">Visualize a localizacao geografica dos membros da igreja</p>
    </div>
    <div class="mgmt-header__actions">
        <span class="mgmt-map-counter"><?= (int) ($locatedMembers ?? 0) ?> de <?= (int) ($totalMembers ?? 0) ?> membros localizados</span>
        <button type="button" class="btn btn--primary" id="refresh-member-map">Atualizar Mapa</button>
    </div>
</div>

<div class="mgmt-member-map-shell">
    <div id="member-map" class="mgmt-member-map" data-members='<?= e(json_encode($mapMembers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT)) ?>'></div>
</div>

<div class="mgmt-map-help">
    <div>
        <strong>Instrucoes</strong>
        <span>Clique em um marcador para ver os detalhes do membro.</span>
    </div>
    <div>
        <strong>Localizacao</strong>
        <span>Os marcadores sao agrupados automaticamente por proximidade.</span>
    </div>
    <div>
        <strong>Adicionar localizacao</strong>
        <span>Edite o membro e clique em "Definir no mapa".</span>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
(function () {
    const mapEl = document.getElementById('member-map');
    if (!mapEl || typeof L === 'undefined') return;

    let members = [];
    try {
        members = JSON.parse(mapEl.dataset.members || '[]');
    } catch (error) {
        members = [];
    }

    const fallbackCenter = [-22.7057, -46.9854];
    const first = members.find((member) => Number.isFinite(member.lat) && Number.isFinite(member.lng));
    const map = L.map(mapEl, { scrollWheelZoom: true }).setView(first ? [first.lat, first.lng] : fallbackCenter, first ? 14 : 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const cluster = L.markerClusterGroup({
        showCoverageOnHover: false,
        maxClusterRadius: 48
    });

    const markerIcon = L.divIcon({
        className: 'mgmt-member-map-marker',
        html: '<span></span>',
        iconSize: [28, 38],
        iconAnchor: [14, 38],
        popupAnchor: [0, -34]
    });

    members.forEach((member) => {
        if (!Number.isFinite(member.lat) || !Number.isFinite(member.lng) || (member.lat === 0 && member.lng === 0)) return;

        const avatar = member.photo
            ? `<img src="${escapeHtml(member.photo)}" alt="">`
            : `<span>${escapeHtml((member.name || '?').slice(0, 1).toUpperCase())}</span>`;
        const popup = `
            <div class="member-map-popup">
                <div class="member-map-popup__head">
                    <div class="member-map-popup__avatar">${avatar}</div>
                    <div>
                        <strong>${escapeHtml(member.name || 'Membro')}</strong>
                        <span>${escapeHtml(member.email || '')}</span>
                    </div>
                </div>
                <div class="member-map-popup__body">
                    ${member.phone ? `<div><b>Telefone</b><span>${escapeHtml(member.phone)}</span></div>` : ''}
                    ${member.address ? `<div><b>Endereco</b><span>${escapeHtml(member.address)}</span></div>` : ''}
                </div>
                <div class="member-map-popup__actions">
                    <a href="https://www.google.com/maps/search/?api=1&query=${member.lat},${member.lng}" target="_blank" rel="noopener">Maps</a>
                    <a href="https://waze.com/ul?ll=${member.lat},${member.lng}&navigate=yes" target="_blank" rel="noopener">Waze</a>
                    <a href="<?= url('/gestao/membros') ?>/${member.id}/editar">Editar</a>
                </div>
            </div>
        `;

        cluster.addLayer(L.marker([member.lat, member.lng], { icon: markerIcon }).bindPopup(popup, { maxWidth: 320 }));
    });

    map.addLayer(cluster);
    if (members.length > 1 && cluster.getLayers().length > 0) {
        map.fitBounds(cluster.getBounds(), { padding: [32, 32] });
    }

    document.getElementById('refresh-member-map')?.addEventListener('click', () => {
        map.invalidateSize();
        if (cluster.getLayers().length > 0) {
            map.fitBounds(cluster.getBounds(), { padding: [32, 32] });
        }
    });

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char]));
    }
})();
</script>
<?php $__view->endSection(); ?>
