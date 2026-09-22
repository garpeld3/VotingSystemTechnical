document.addEventListener('DOMContentLoaded', () => {
  const loginTabs = document.querySelectorAll('.tab-btn');
  const loginForms = document.querySelectorAll('.auth-form');

  loginTabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.tab;

      loginTabs.forEach((btn) => {
        const isActive = btn === tab;
        btn.classList.toggle('active', isActive);
        btn.setAttribute('aria-selected', String(isActive));
      });

      loginForms.forEach((form) => {
        form.classList.toggle('active', form.dataset.form === target);
      });
    });
  });

  const reportCards = document.querySelectorAll('.report-card');
  const detailContent = document.getElementById('detailContent');
  const markers = document.querySelectorAll('.marker');

  const detailMap = {
    1: {
      title: 'Large pothole near Maple Ave',
      type: 'Pothole',
      location: 'Maple Ave & 7th Street',
      status: 'Under review',
      count: '12 nearby reports',
      tone: 'danger',
    },
    2: {
      title: 'Broken streetlight',
      type: 'Streetlight',
      location: 'Market Square',
      status: 'City crew assigned',
      count: '6 nearby reports',
      tone: 'warning',
    },
    3: {
      title: 'Graffiti removed from park wall',
      type: 'Graffiti',
      location: 'Lakeside Park',
      status: 'Cleanup scheduled',
      count: '3 nearby reports',
      tone: 'success',
    },
  };

  function renderDetail(id) {
    const item = detailMap[id];
    if (!item || !detailContent) return;

    detailContent.innerHTML = `
      <div class="detail-card">
        <span class="tag ${item.tone}">${item.type}</span>
        <h4>${item.title}</h4>
        <ul>
          <li><strong>Location:</strong> ${item.location}</li>
          <li><strong>Status:</strong> ${item.status}</li>
          <li><strong>Residents:</strong> ${item.count}</li>
        </ul>
      </div>
    `;
  }

  reportCards.forEach((card) => {
    card.addEventListener('click', () => {
      const id = card.dataset.id;
      reportCards.forEach((item) => item.classList.toggle('active', item === card));
      markers.forEach((marker) => {
        marker.classList.toggle('active', marker.dataset.id === id);
      });
      renderDetail(id);
    });
  });

  markers.forEach((marker) => {
    marker.addEventListener('click', () => {
      const id = marker.dataset.id;
      reportCards.forEach((card) => card.classList.toggle('active', card.dataset.id === id));
      markers.forEach((item) => item.classList.toggle('active', item === marker));
      renderDetail(id);
    });
  });

  const modal = document.getElementById('reportModal');
  const openFormButtons = document.querySelectorAll('.js-open-form');
  const closeFormButtons = document.querySelectorAll('.js-close-form');

  function toggleModal(show) {
    if (!modal) return;
    modal.classList.toggle('open', show);
    modal.setAttribute('aria-hidden', String(!show));
  }

  openFormButtons.forEach((button) => {
    button.addEventListener('click', () => toggleModal(true));
  });

  closeFormButtons.forEach((button) => {
    button.addEventListener('click', () => toggleModal(false));
  });

  modal?.addEventListener('click', (event) => {
    if (event.target === modal) toggleModal(false);
  });

  const priorityButtons = document.querySelectorAll('.priority-btn');
  priorityButtons.forEach((button) => {
    button.addEventListener('click', () => {
      priorityButtons.forEach((item) => item.classList.toggle('active', item === button));
    });
  });

  const reportForm = document.querySelector('.report-form');
  reportForm?.addEventListener('submit', (event) => {
    event.preventDefault();
    toggleModal(false);
    reportForm.reset();
    priorityButtons.forEach((button) => button.classList.toggle('active', button.textContent.trim() === 'Low'));
  });
});
