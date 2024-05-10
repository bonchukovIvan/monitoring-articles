$(document).ready(function() {
    $('.more-btn').each(function() {
        $(this).click(function() {
            $(this).next('.item__expand').toggleClass("open");
            $(this).find('.arrow').toggleClass("up down");
        });
    });
    $('#all-sites-check').click(function() {
        const selected = $('#custom_date').find(":selected").val();
        window.location.href = window.location.href.replace( /[\?#].*|$/, "?type=all&custom_date="+selected );
        $(this).html("Почекайте...");
    });

    $('#partially-sites-check').click(function() {
        window.location.href = window.location.href.replace( /[\?#].*|$/, "?type=partially" );
    });


    $('#partially-sites-check-run').click(function() {
        let ids = '&';
        const checkedIds = $('#partially-checkeded input[type="checkbox"]:checked').map(function() {
            if (this.id == "select-all") {
                return;
            }
            return this.id;
        }).get();
        checkedIds.forEach((elem) => {
            ids += 'foo[]='+elem+'&';
        });

        const selected = $('#custom_date').find(":selected").val();

        window.location.href = window.location.href.replace( /[\?#].*|$/, "?type=partially&run=1"+ids+'custom_date='+selected );
        $(this).html("Почекайте...");
    }).prop('value', 'Save');

    $('#select-all').click(function(event) {   
        if(this.checked) {
            $('#partially-checkeded :checkbox').each(function() {
                this.checked = true;                        
            });
        } else {
            $('#partially-checkeded :checkbox').each(function() {
                this.checked = false;                       
            });
        }
    }); 
});