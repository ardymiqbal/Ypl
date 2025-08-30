@once
  {{-- Lightbox / fullscreen modal (global) --}}
  <div id="lightbox" class="fixed inset-0 hidden z-50 bg-black/90 backdrop-blur-sm">
      <div class="absolute top-4 right-4 flex items-center gap-2">
          <span id="lightboxTitle" class="text-white/90 text-sm"></span>
          <button type="button" id="btnCloseLightbox"
                  class="px-3 py-1 rounded bg-white/20 text-white hover:bg-white/30">
              Tutup ✕
          </button>
      </div>
      <div class="w-full h-full flex items-center justify-center p-4">
          <div id="lightboxBody" class="max-w-[95vw] max-h-[95vh]"></div>
      </div>
  </div>

  <script>
  (function () {
      // guard supaya hanya inisialisasi sekali
      if (window.__mediaLightboxInit) return; window.__mediaLightboxInit = true;

      const lb       = document.getElementById('lightbox');
      const body     = document.getElementById('lightboxBody');
      const titleEl  = document.getElementById('lightboxTitle');
      const btnClose = document.getElementById('btnCloseLightbox');

      function openMediaModal(title, type, src) {
          if (!lb || !body) return;
          if (titleEl) titleEl.textContent = title || '';
          body.innerHTML = '';

          if (type === 'video') {
              const v = document.createElement('video');
              v.controls = true; v.preload = 'metadata'; v.src = src;
              v.className = 'max-w-full max-h-[95vh] w-auto h-auto object-contain rounded bg-black';
              body.appendChild(v);
          } else {
              const img = document.createElement('img');
              img.src = src; img.alt = title || 'media';
              img.className = 'max-w-full max-h-[95vh] w-auto h-auto object-contain rounded';
              body.appendChild(img);
          }

          lb.classList.remove('hidden');
      }

      function closeMediaModal() {
          if (!lb || !body) return;
          lb.classList.add('hidden');
          body.innerHTML = '';
      }

      function onBackdropClick(e){ if (e.target === lb) closeMediaModal(); }
      function onEscClose(e){ if (e.key === 'Escape') closeMediaModal(); }

      // Delegasi klik untuk semua elemen dengan .js-open-media
      document.addEventListener('click', function (e) {
          const btn = e.target.closest('.js-open-media');
          if (!btn) return;
          openMediaModal(btn.dataset.title || '',
                         btn.dataset.type  || 'image',
                         btn.dataset.src   || '');
      });

      lb && lb.addEventListener('click', onBackdropClick);
      btnClose && btnClose.addEventListener('click', closeMediaModal);
      window.addEventListener('keydown', onEscClose);

      // expose jika butuh dipanggil manual
      window.closeMediaModal = closeMediaModal;
      window.openMediaModal  = openMediaModal;
  })();
  </script>
@endonce
