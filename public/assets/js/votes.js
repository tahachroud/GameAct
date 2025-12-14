// public/assets/js/votes.js
// Robuste : cooldown 2000ms par feedback + disable pendant le fetch.
// Utilise dataset.cooldown pour être résistant au rerender.

(function() {
  const COOLDOWN_MS = 2000;

  // helper: disable/enable buttons of a feedback
  function setButtonsState(feedbackId, disabled) {
    const like = document.getElementById('like-btn-' + feedbackId);
    const dislike = document.getElementById('dislike-btn-' + feedbackId);
    [like, dislike].forEach(b => {
      if (!b) return;
      b.disabled = disabled;
      b.style.pointerEvents = disabled ? 'none' : '';
      b.style.opacity = disabled ? '0.6' : '';
    });
  }

  // helper: start cooldown on a feedback element (prevents re-entrance)
  function startCooldown(feedbackId) {
    const like = document.getElementById('like-btn-' + feedbackId);
    const dislike = document.getElementById('dislike-btn-' + feedbackId);
    const now = Date.now();
    [like, dislike].forEach(b => {
      if (!b) return;
      b.dataset.cooldown = String(now); // mark cooldown start time
    });
    // remove cooldown marker after COOLDOWN_MS
    setTimeout(() => {
      [like, dislike].forEach(b => { if (b) delete b.dataset.cooldown; });
    }, COOLDOWN_MS);
  }

  // check if feedback is in cooldown
  function isInCooldown(feedbackId) {
    const like = document.getElementById('like-btn-' + feedbackId);
    if (!like) return false;
    return !!like.dataset.cooldown;
  }

  // main click handler (delegation)
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-vote');
    if (!btn) return;
    e.preventDefault();

    const feedbackId = btn.dataset.id;
    const voteType = parseInt(btn.dataset.vote, 10); // 1 or -1

    if (!feedbackId || ![1, -1].includes(voteType)) return;

    // If cooldown active for this feedback, ignore click
    if (isInCooldown(feedbackId)) {
      // optional: give immediate feedback (flash)
      btn.classList.add('flash-blocked');
      setTimeout(() => btn.classList.remove('flash-blocked'), 300);
      return;
    }

    // mark cooldown immediately so fast repeated clicks are ignored
    startCooldown(feedbackId);

    // disable buttons during the request
    setButtonsState(feedbackId, true);

    // prepare request (legacy format id/type)
    const body = new URLSearchParams();
    body.append('id', feedbackId);
    body.append('type', voteType === 1 ? 'like' : 'dislike');

    fetch('/ajax/feedback.php', {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body.toString()
    })
    .then(r => r.json().catch(() => ({ status: 'error' })))
    .then(json => {
      // success legacy: {status: 'success'} OR new endpoint: { success:true, data:{counts,userVote} }
      if (json && json.status === 'success') {
        // naive increment (legacy)
        if (body.get('type') === 'like') {
          const likeSpan = document.getElementById('likes-' + feedbackId);
          if (likeSpan) likeSpan.textContent = parseInt(likeSpan.textContent || '0', 10) + 1;
        } else {
          const dis = document.getElementById('dislikes-' + feedbackId);
          if (dis) dis.textContent = parseInt(dis.textContent || '0', 10) + 1;
        }
      } else if (json && json.success === true && json.data && json.data.counts) {
        const counts = json.data.counts;
        const likeSpan = document.getElementById('likes-' + feedbackId);
        const dislikeSpan = document.getElementById('dislikes-' + feedbackId);
        if (likeSpan) likeSpan.textContent = counts.likes;
        if (dislikeSpan) dislikeSpan.textContent = counts.dislikes;

        // update active classes if provided
        if (typeof json.data.userVote !== 'undefined') {
          const likeBtn = document.getElementById('like-btn-' + feedbackId);
          const dislikeBtn = document.getElementById('dislike-btn-' + feedbackId);
          if (likeBtn) likeBtn.classList.toggle('active', json.data.userVote === 1);
          if (dislikeBtn) dislikeBtn.classList.toggle('active', json.data.userVote === -1);
        }
      } else {
        console.warn('Vote response unexpected', json);
      }
    })
    .catch(err => {
      console.error('Vote error', err);
      alert('Erreur réseau lors du vote.');
    })
    .finally(() => {
      // Ensure buttons remain disabled for the full cooldown duration.
      // Re-enable after COOLDOWN_MS
      setTimeout(() => {
        setButtonsState(feedbackId, false);
      }, COOLDOWN_MS);
    });
  });

  // small CSS injection for quick feedback (optional; you can copy this to your CSS file)
  (function injectCSS() {
    const css = `
    .btn-vote.flash-blocked { animation: blockedFlash 0.25s ease; }
    @keyframes blockedFlash { 0% { transform: scale(1); } 50% { transform: scale(0.98); } 100% { transform: scale(1); } }
    `;
    const s = document.createElement('style'); s.textContent = css; document.head.appendChild(s);
  })();

})();
