$(document).ready(function() {
    let products = window.__products || [];
    let $tbody = $('#items-table tbody');

    function recalcGrandTotal() {
        let grand = 0;
        $tbody.find('tr').each(function(){
            let qty = parseFloat($(this).find('.item-qty').val()) || 0;
            let price = parseFloat($(this).find('.item-price').val()) || 0;
            let discount = parseFloat($(this).find('.item-discount').val()) || 0;
            let line = (qty * price) - discount;
            $(this).find('.line-total').text(line.toFixed(2));
            grand += line;
        });
        $('#grand-total').text(grand.toFixed(2));
    }

    function addRow(item = {}) {
        let $row = $(`
            <tr>
                <td>
                    <select name="items[][product_id]" class="form-control item-product" required>
                        <option value="">-- Select product --</option>
                        ${products.map(p => `<option value="${p.id}" ${item.product_id == p.id ? 'selected' : ''}>${p.name}</option>`).join('')}
                    </select>
                </td>
                <td><input type="number" name="items[][quantity]" class="form-control item-qty" min="1" value="${item.quantity || 1}" required></td>
                <td><input type="number" name="items[][price]" class="form-control item-price" step="0.01" value="${item.price || 0}" required></td>
                <td><input type="number" name="items[][discount]" class="form-control item-discount" step="0.01" value="${item.discount || 0}"></td>
                <td class="line-total">0.00</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
            </tr>
        `);
        $tbody.append($row);
        recalcGrandTotal();
    }

    $('#add-row').click(function(){ addRow(); });
    $tbody.on('click', '.remove-row', function(){ $(this).closest('tr').remove(); recalcGrandTotal(); });
    $tbody.on('input change', '.item-qty, .item-price, .item-discount', recalcGrandTotal);
    $tbody.on('change', '.item-product', function(){
        let pid = $(this).val();
        let prod = products.find(p=>p.id==pid);
        if(prod) $(this).closest('tr').find('.item-price').val(prod.price);
        recalcGrandTotal();
    });

    // Initialize with one row
    if($tbody.find('tr').length === 0) addRow();

    // Optional: AJAX submit
    $('#sale-form').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(res){
                alert(res.message);
                window.location.reload();
            },
            error: function(err){
                alert('Error! Check form inputs.');
            }
        });
    });
});
