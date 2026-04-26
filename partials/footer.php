</main>

    <!-- =========================
         Följ mig
         ========================= -->
    <section class="section" aria-label="Följ mig">
      <div class="paper">
        <div class="paper__inner">

          <header class="intro" style="padding: 18px 0 22px;">
            <h2 style="margin:0; font-size:18px; letter-spacing:.18em; text-transform:uppercase; color:#7a7a7a;">
              Följ mig
            </h2>
          </header>

          <div class="social">
            <a class="social__card" href="https://www.instagram.com/akajos/" target="_blank" rel="noopener">
              <span class="social__icon" aria-hidden="true">IG</span>
              <span class="social__meta">
                <span class="social__name">Instagram</span>
                <span class="social__handle">@akajos</span>
              </span>
              <span class="social__arrow" aria-hidden="true">→</span>
            </a>

            <a class="social__card" href="https://www.facebook.com/akajos" target="_blank" rel="noopener">
              <span class="social__icon" aria-hidden="true">FB</span>
              <span class="social__meta">
                <span class="social__name">Facebook</span>
                <span class="social__handle">/akajos</span>
              </span>
              <span class="social__arrow" aria-hidden="true">→</span>
            </a>

            <a class="social__card" href="https://gansub.com/s/7wVXrv3lfDyME/" target="_blank" rel="noopener">
              <span class="social__icon" aria-hidden="true">NB</span>
              <span class="social__meta">
                <span class="social__name">Nyhetsbrev</span>
                <span class="social__handle">christerbarregren</span>
              </span>
              <span class="social__arrow" aria-hidden="true">→</span>
            </a>
          </div>

        </div>
      </div>
    </section>

    <!-- =========================
         Footer
         ========================= -->
    <footer>
    <img src="/assets/img/logo.png" alt="" class="footer-logo">
    
      © <span id="year"></span> Christer Barregren. Här är hans <a href="programforklaring.php" class="textlink">programförklaring</a>.
    </footer>

  </main>

  <!-- =========================
       År (säker)
       ========================= -->
  <script>
    (function () {
      const yearEl = document.getElementById("year");
      if (yearEl) {
        yearEl.textContent = new Date().getFullYear();
      }
    })();
  </script>

  <!-- =========================
       Mobilmeny (säker)
       ========================= -->
  <script>
    (function () {
      const btn = document.querySelector('.nav-toggle');
      const nav = document.querySelector('#mainnav');
      if (!btn || !nav) return;

      btn.addEventListener('click', function () {
        const open = nav.classList.toggle('is-open');
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      });

      nav.addEventListener('click', function (e) {
        if (e.target && e.target.tagName === 'A') {
          nav.classList.remove('is-open');
          btn.setAttribute('aria-expanded', 'false');
        }
      });
    })();
  </script>

  <!-- =========================
       Ålder (säker, sidoberoende)
       ========================= -->
  <script>
    function calculateAge(birthDate) {
      const today = new Date();
      const birth = new Date(birthDate);

      let age = today.getFullYear() - birth.getFullYear();
      const hasHadBirthday =
        today.getMonth() > birth.getMonth() ||
        (today.getMonth() === birth.getMonth() && today.getDate() >= birth.getDate());

      if (!hasHadBirthday) age--;

      return age;
    }

    (function () {
      const ageEl = document.getElementById("christer-age");
      if (ageEl) {
        ageEl.textContent = calculateAge("1970-09-05");
      }
    })();
  </script>

</body>
</html>