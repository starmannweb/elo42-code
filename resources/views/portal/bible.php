<?php $__view->extends('layouts/portal'); ?>

<?php $__view->section('content'); ?>
<div style="max-width: 800px; margin: 0 auto; padding-top: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin: 0;">Bíblia Sagrada</h2>
        <div style="position: relative;">
            <select style="appearance: none; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.5rem 2rem 0.5rem 1rem; font-size: 0.85rem; font-weight: 500; cursor: pointer;">
                <option>ARA</option>
                <option>ARC</option>
                <option>ACF</option>
                <option>NVI</option>
            </select>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); pointer-events: none;"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
    </div>

    <div style="position: relative; margin-bottom: 1.5rem;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" placeholder="Buscar livro ou versículo..." style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.5rem; border-radius: 12px; border: 1px solid #e5e7eb; background: #f9fafb; font-size: 0.95rem; box-sizing: border-box;">
    </div>

    <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; background: #f9fafb; padding: 0.25rem; border-radius: 12px;">
        <button style="flex: 1; padding: 0.6rem; border: none; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); font-weight: 600; font-size: 0.85rem; color: var(--portal-text); cursor: pointer;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 7H20"></path><path d="M6.5 7v10"></path></svg> Livros</button>
        <button style="flex: 1; padding: 0.6rem; border: none; background: transparent; font-weight: 500; font-size: 0.85rem; color: #6b7280; cursor: pointer;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg> Favoritos</button>
        <button style="flex: 1; padding: 0.6rem; border: none; background: transparent; font-weight: 500; font-size: 0.85rem; color: #6b7280; cursor: pointer;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg> Grifados</button>
    </div>

    <div style="display: flex; gap: 0.75rem; margin-bottom: 2rem;">
        <button style="flex: 1; padding: 0.75rem; border: none; background: #1e3a8a; color: white; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer;">Antigo Testamento</button>
        <button style="flex: 1; padding: 0.75rem; border: 1px solid #e5e7eb; background: #fff; color: #4b5563; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer;">Novo Testamento</button>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <!-- Left Column -->
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="background: #fff; border: 1px solid #f3f4f6; border-radius: 12px; padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: all 0.2s;">
                <div>
                    <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">Gênesis</div>
                    <div style="font-size: 0.75rem; color: #6b7280;">50 capítulos</div>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            <div style="background: #fff; border: 1px solid #f3f4f6; border-radius: 12px; padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                <div>
                    <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">Êxodo</div>
                    <div style="font-size: 0.75rem; color: #6b7280;">40 capítulos</div>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            <div style="background: #fff; border: 1px solid #f3f4f6; border-radius: 12px; padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                <div>
                    <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">Levítico</div>
                    <div style="font-size: 0.75rem; color: #6b7280;">27 capítulos</div>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </div>

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="background: #fff; border: 1px solid #f3f4f6; border-radius: 12px; padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                <div>
                    <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">Números</div>
                    <div style="font-size: 0.75rem; color: #6b7280;">36 capítulos</div>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            <div style="background: #fff; border: 1px solid #f3f4f6; border-radius: 12px; padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                <div>
                    <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">Deuteronômio</div>
                    <div style="font-size: 0.75rem; color: #6b7280;">34 capítulos</div>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            <div style="background: #fff; border: 1px solid #f3f4f6; border-radius: 12px; padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                <div>
                    <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">Josué</div>
                    <div style="font-size: 0.75rem; color: #6b7280;">24 capítulos</div>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </div>
    </div>
</div>
<?php $__view->endSection(); ?>
