async function nominatimSearchTZ(q){
  const url = 'https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&countrycodes=tz&q='+encodeURIComponent(q);
  const r = await fetch(url, {headers:{'Accept':'application/json'}});
  if(!r.ok) return [];
  return await r.json();
}

function attachSearch(inputId, onPick){
  const input = document.getElementById(inputId);
  const box = document.createElement('div');
  box.style.position='absolute'; box.style.zIndex='9999';
  box.style.background='#fff'; box.style.border='1px solid #eee'; box.style.borderRadius='12px'; box.style.padding='6px';
  box.style.width='100%'; box.style.maxHeight='220px'; box.style.overflow='auto';
  input.parentElement.style.position='relative';
  input.parentElement.appendChild(box);

  let timer=null;
  input.addEventListener('input', ()=>{
    const q = input.value.trim();
    clearTimeout(timer);
    if(q.length<3){ box.innerHTML=''; return; }
    timer=setTimeout(async ()=>{
      box.innerHTML='Inatafuta...';
      const data = await nominatimSearchTZ(q);
      box.innerHTML='';
      data.slice(0,8).forEach(item=>{
        const row = document.createElement('div');
        row.style.padding='6px 8px'; row.style.cursor='pointer';
        row.textContent = item.display_name;
        row.onclick = ()=>{
          box.innerHTML='';
          onPick(parseFloat(item.lat), parseFloat(item.lon), item.display_name);
        };
        box.appendChild(row);
      });
    }, 350);
  });

  document.addEventListener('click', (e)=>{
    if(!box.contains(e.target) && e.target!==input){ box.innerHTML=''; }
  });
}
