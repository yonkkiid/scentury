document.addEventListener('DOMContentLoaded',()=>{
	const path = location.pathname;
	document.querySelectorAll('.nav a').forEach(a=>{
		if(a.getAttribute('href') && path.endsWith(a.getAttribute('href').split('/').pop())){
			a.classList.add('active');
		}
	});

	document.querySelectorAll('form[data-validate]')?.forEach(form=>{
		form.addEventListener('submit', (e)=>{
			let valid = true;
			form.querySelectorAll('[required]')?.forEach(inp=>{
				if(!inp.value){
					valid = false;
					inp.classList.add('invalid');
				}
			});
			if(!valid){
				e.preventDefault();
				alert('Пожалуйста, заполните обязательные поля');
			}
		});
	});

	// Feedback modal handlers
	const modal = document.getElementById('feedback-modal');
	const openButtons = document.querySelectorAll('[data-open-feedback]');
	const closeButtons = document.querySelectorAll('[data-close-feedback]');
	const setOpen = (isOpen)=>{
		if(!modal) return;
		modal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
		if(isOpen){
			document.body.style.overflow = 'hidden';
		} else {
			document.body.style.overflow = '';
		}
	};
	openButtons.forEach(btn=>btn.addEventListener('click',()=>setOpen(true)));
	closeButtons.forEach(btn=>btn.addEventListener('click',()=>setOpen(false)));
	window.addEventListener('keydown',(e)=>{ if(e.key==='Escape') setOpen(false); });
});

// Booking seat map interactions
document.addEventListener('DOMContentLoaded', ()=>{
  const seatClassSelect = document.querySelector('select[name="seat_class"]');
  const seatMap = document.querySelector('.seat-map');
  const seatInput = document.querySelector('input[name="seat_id"]');
  if (!seatMap || !seatClassSelect || !seatInput) return;

  const applyFilters = ()=>{
    const cls = seatClassSelect.value;
    seatMap.querySelectorAll('.seat[data-id]')?.forEach(el=>{
      const isClassOk = cls ? (el.getAttribute('data-class')===cls) : true;
      const isDisabled = el.classList.contains('disabled');
      el.style.display = isClassOk ? '' : 'none';
      if (!isClassOk && el.classList.contains('selected')){
        el.classList.remove('selected');
        seatInput.value = '';
      }
      if (!isDisabled) el.tabIndex = isClassOk ? 0 : -1;
    });
  };

  seatMap.addEventListener('click', (e)=>{
    const el = e.target.closest('.seat[data-id]');
    if (!el || el.classList.contains('disabled')) return;
    seatMap.querySelectorAll('.seat.selected')?.forEach(s=>s.classList.remove('selected'));
    el.classList.add('selected');
    seatInput.value = el.getAttribute('data-id');
  });

  seatClassSelect.addEventListener('change', applyFilters);
  applyFilters();
});


