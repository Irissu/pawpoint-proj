
const navEl = document.querySelector('.navbar');
const navbarToggler = document.querySelector('.navbar-toggler');
const navbarCollapse = document.querySelector('.navbar-collapse');

window.addEventListener('scroll', () => {
    if (window.scrollY > 60) {
        navEl.classList.add('navbar-scrolled');
        navEl.classList.remove('navbar-open');
    } else {
        navEl.classList.remove('navbar-scrolled');
        if (navbarCollapse.classList.contains('show')) {
          navEl.classList.add('navbar-open');
        }
    }
});

  const navLinks = document.querySelectorAll('.nav-link');

  navLinks.forEach(link => {
    link.addEventListener('click', function () {
      navLinks.forEach(item => item.classList.remove('active'));

      this.classList.add('active');
    });
  });

  navbarToggler.addEventListener('click', () => {
    if (navbarCollapse.classList.contains('show')) {
      navEl.classList.remove('navbar-open');
    } else if (window.scrollY <= 60) {
      navEl.classList.add('navbar-open');
    }
  });