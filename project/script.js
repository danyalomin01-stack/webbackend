(function(){
  var form = document.getElementById('contactForm');
  if(!form || !window.fetch) return;
  var result = document.getElementById('result');
  form.addEventListener('submit', function(e){
    e.preventDefault();
    result.textContent = '';
    Array.prototype.forEach.call(form.querySelectorAll('.field-error'), function(el){ el.classList.remove('field-error'); });
    var btn = form.querySelector('button');
    btn.disabled = true;
    btn.textContent = 'ОТПРАВКА...';
    var fd = new FormData(form);
    var data = {};
    fd.forEach(function(v,k){ data[k]=v; });
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
          result.textContent = text.join(' ') || 'Ошибка отправки.';
          return;
        }
        if(res.data.login){
          result.innerHTML = 'Заявка сохранена.<br>Логин: <b>'+res.data.login+'</b><br>Пароль: <b>'+res.data.password+'</b><br>Профиль: <a style="color:#fff" href="'+res.data.profile+'">открыть</a>';
        } else {
          result.textContent = res.data.message || 'Данные сохранены.';
        }
      })
      .catch(function(){ result.textContent = 'Ошибка сети. Попробуйте еще раз.'; })
      .finally(function(){ btn.disabled=false; btn.textContent = form.dataset.method === 'PUT' ? 'СОХРАНИТЬ' : 'СВЯЖИТЕСЬ С НАМИ'; });
  });
})();
