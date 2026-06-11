document.addEventListener('DOMContentLoaded', function(){
    // Chart placeholder: simple bar chart for demo
    var ctx = document.getElementById('peminjamanChart');
    if(ctx){
        var demo = window.DEMO_CHART_DATA || {labels:['Jan','Feb','Mar','Apr','Mei','Jun'], data:[10,15,12,20,18,14]};
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: demo.labels,
                datasets: [{
                    label: 'Peminjaman',
                    data: demo.data,
                    backgroundColor: '#3b82f6'
                }]
            },
            options: {
                responsive:true,
                plugins:{legend:{display:false}}
            }
        });
    }

    // Modal open/close
    document.querySelectorAll('[data-modal-open]').forEach(function(btn){
        btn.addEventListener('click', function(){
            var id = btn.getAttribute('data-modal-open');
            var modal = document.getElementById(id);
            if(modal) modal.style.display = 'block';
        });
    });
    document.querySelectorAll('[data-modal-close]').forEach(function(btn){
        btn.addEventListener('click', function(){
            var id = btn.getAttribute('data-modal-close');
            var modal = document.getElementById(id);
            if(modal) modal.style.display = 'none';
        });
    });
});