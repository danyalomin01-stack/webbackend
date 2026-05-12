(function(){
  var burger = document.querySelector('.burger');
  var mobile = document.querySelector('.nav__list.mobile');
  var subToggle = document.querySelector('.mobile-sub-toggle');
  var subs = document.querySelectorAll('.mobile-submenu');
  if (burger && mobile) {
    burger.addEventListener('click', function(){ burger.classList.toggle('active'); mobile.classList.toggle('open'); });
    mobile.addEventListener('click', function(e){ if(e.target.tagName === 'A'){ burger.classList.remove('active'); mobile.classList.remove('open'); } });
  }
  if (subToggle) {
    subToggle.addEventListener('click', function(){ subs.forEach(function(x){ x.classList.toggle('show'); }); });
  }

  var modal = document.getElementById('contactModal');
  document.querySelectorAll('.js-open-modal').forEach(function(btn){
    btn.addEventListener('click', function(){ if(modal) modal.hidden = false; });
  });
  if (modal) {
    modal.querySelector('.modal-close').addEventListener('click', function(){ modal.hidden = true; });
    modal.addEventListener('click', function(e){ if(e.target === modal) modal.hidden = true; });
  }

  document.querySelectorAll('.faq-q').forEach(function(btn){
    btn.addEventListener('click', function(){ btn.closest('.faq-item').classList.toggle('open'); });
  });

  document.querySelectorAll('input[name="phone"]').forEach(function(input){
    input.addEventListener('input', function(){
      var v = input.value.replace(/[^0-9+\-\s()]/g, '');
      if (v.indexOf('+') > 0) v = v.replace(/\+/g, '');
      input.value = v;
    });
  });

  if(!window.fetch) return;
  document.querySelectorAll('.js-contact-form').forEach(function(form){
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var result = form.querySelector('.form-result');
      if(result) result.textContent = '';
      form.querySelectorAll('.field-error').forEach(function(el){ el.classList.remove('field-error'); });
      var btn = form.querySelector('button[type="submit"]');
      var oldText = btn ? btn.textContent : '';
      if(btn){ btn.disabled = true; btn.textContent = 'Отправка...'; }
      var fd = new FormData(form), data = {};
      fd.forEach(function(v,k){ data[k] = v; });
      fetch(form.dataset.api, {
        method: form.dataset.method || 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify(data)
      }).then(function(r){ return r.json().then(function(j){ return {ok:r.ok, data:j}; }); })
        .then(function(res){
          if(!res.ok){
            var text = [];
            if(res.data && res.data.errors){
              Object.keys(res.data.errors).forEach(function(k){
                text.push(res.data.errors[k]);
                var f = form.querySelector('[name="'+k+'"]'); if(f) f.classList.add('field-error');
              });
            }
            if(result) result.textContent = text.join(' ') || 'Ошибка отправки.';
            return;
          }
          if(res.data.login){
            if(result) result.innerHTML = 'Заявка сохранена.<br>Логин: <b>'+res.data.login+'</b><br>Пароль: <b>'+res.data.password+'</b><br>Профиль: <a href="'+res.data.profile+'">открыть</a>';
            form.reset();
          } else {
            if(result) result.textContent = res.data.message || 'Данные сохранены.';
          }
        })
        .catch(function(){ if(result) result.textContent = 'Ошибка сети. Попробуйте еще раз.'; })
        .finally(function(){ if(btn){ btn.disabled = false; btn.textContent = oldText; } });
    });
  });
})();
