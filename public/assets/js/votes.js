document.addEventListener('click', function (e) {
  const btn = e.target.closest('.btn-vote');
  if (!btn) return;

  e.preventDefault();

  if (btn.dataset.locked === '1') return;
  btn.dataset.locked = '1';

  const feedbackId = btn.dataset.id;
  const type = btn.dataset.vote === '1' ? 'like' : 'dislike';

  const body = new URLSearchParams();
  body.append('id', feedbackId);
  body.append('type', type);

  fetch('/ajax/feedback.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: body.toString()
  })
    .then(r => r.json())
    .then(json => {
      if (!json.success) {
        alert('Erreur vote');
        return;
      }

      const { likes, dislikes } = json.data.counts;
      const userVote = json.data.userVote;

      animateCounter('likes-' + feedbackId, likes);
      animateCounter('dislikes-' + feedbackId, dislikes);

      document.querySelectorAll(`.btn-vote[data-id="${feedbackId}"]`)
        .forEach(b => b.classList.remove('active'));

      if (userVote === 1) {
        document.querySelector(`.btn-vote[data-id="${feedbackId}"][data-vote="1"]`)
          .classList.add('active');
      }
      if (userVote === -1) {
        document.querySelector(`.btn-vote[data-id="${feedbackId}"][data-vote="-1"]`)
          .classList.add('active');
      }
    })
    .catch(() => alert('Erreur réseau'))
    .finally(() => {
      setTimeout(() => btn.dataset.locked = '0', 600);
    });
});

function animateCounter(id, value) {
  const el = document.getElementById(id);
  if (!el) return;

  el.classList.add('counter-pop');
  el.textContent = value;

  setTimeout(() => el.classList.remove('counter-pop'), 300);
}
