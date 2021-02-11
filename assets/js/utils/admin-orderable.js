import Sortable from "sortablejs";

const tableBody = document.querySelector('.content-panel tbody');

// add a header col for draggable icon
const header_col = document.createElement('th');
header_col.setAttribute('width', "10px");
document.querySelector('.content-panel thead tr').prepend(header_col);

tableBody.querySelectorAll('tr').forEach(function(tr){
    const td = document.createElement("td");
    const icon = document.createElement("i");
    icon.className = "fa fa-bars sort-handle";
    td.appendChild(icon);
    tr.prepend(td);
})

Sortable.create(tableBody, {
    handle: '.sort-handle',
    animation: 150,
    onSort: function(evt){
        // Send this to backend to save data
        const url = document.querySelector('#backend-order-url-holder').dataset.url;
        fetch(url, {
            headers: new Headers({
                "X-Requested-With": "XMLHttpRequest"  // Follow common headers
            }),
            method: 'POST',
            body: JSON.stringify({
                'order': JSON.stringify(this.toArray())
            })
        }).then(function(response){
            if(response.ok) {
                $('#order-toast .toast-body').text('Ordre enregistré');
                $('#order-toast').toast('show');
            } else {
                $('#order-toast .toast-body').text("Erreur lors de la sauvegarde de l'ordre");
                $('#order-toast').toast('show');

            }
        }).catch(function(error){
            $('#order-toast .toast-body').text(error);
            $('#order-toast').toast('show');
        });
    }
});