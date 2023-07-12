

const avQuantityInput = document.querySelector('.box_avialable');
const quantityInput = document.querySelector('.box2');

avQuantityInput.addEventListener('select', validateForm);
quantityInput.addEventListener('input', validateForm);

function validateForm() {
    const avQuantity = parseInt(avQuantityInput.value);
    const quantity = parseInt(quantityInput.value);

    if (quantity > avQuantity) {
        // Set a custom error message for the quantityInput
        quantityInput.setCustomValidity("Quantity cannot be greater than Av Quantity!");
    } else {
        // Reset the error message if the validation passes
        quantityInput.setCustomValidity("");
    }
}


var rentDateInputs = document.getElementsByClassName('rent-date-input');

for (var i = 0; i < rentDateInputs.length; i++) {
    rentDateInputs[i].addEventListener('input', validateDates);
}

function validateDates() {
    var rentDateInput = document.getElementsByClassName('rent-date-input')[0];
    var returnDateInput = document.getElementsByClassName('rent-date-input')[1];
    var daysCountInput = document.getElementById('days-count-input');
    var rentDate = new Date(rentDateInput.value);
    var returnDate = new Date(returnDateInput.value);
    if (returnDate < rentDate) {
        returnDateInput.setCustomValidity('Return date cannot be earlier than rent date');
    } else {
        returnDateInput.setCustomValidity('');
        var timeDiff = Math.abs(returnDate.getTime() - rentDate.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        daysCountInput.value = diffDays;
    }
}
$(document).ready(function() {
    $('#example').DataTable({
        lengthMenu: [
            [20, 25, 50, -1],
            [20, 25, 50, 'All'],
        ]
    });
});


// Get all elements with the specified class name
var rentDateInputs = document.getElementsByClassName("rent-date-input");

// Get the current date
var today = new Date().toISOString().split('T')[0];

// Set the minimum value for each input field to today's date
for (var i = 0; i < rentDateInputs.length; i++) {
    rentDateInputs[i].setAttribute('min', today);
}



function code() {
    var box1 = document.getElementsByClassName('box1');
    var box2 = document.getElementsByClassName('box2');
    var costs = document.getElementsByClassName('cost');

    for (var i = 0; i < costs.length; i++) {
        var total = parseFloat(box1[i].value) * parseFloat(box2[i].value);
        costs[i].value = total;
    }
}


$(document).ready(function() {
    $(document).on('change', '.get_product', function() {

        var product_id = this.value;
        var $box1 = $(this).closest('.row').find('.box1');

        $.ajax({
            url: "get_price",
            type: "POST",
            data: {
                product_id: product_id,
            },
            cache: false,
            success: function(result) {
                $box1.html(result);
            }
        });
    });
});
$(document).ready(function() {
    $(document).on('change', '.get_quantity', function() {

        var product_id = this.value;
        var $box_available = $(this).closest('.row').find('.box_avialable');

        $.ajax({
            url: "get_quantiry",
            type: "POST",
            data: {
                product_id: product_id,
            },
            cache: false,
            success: function(result) {
                $box_available.html(result);
            }
        });
    });
});


function validateQuantity(input) {
    const avQuantityInput = input.parentNode.parentNode.querySelector('.box_avialable');
    const avQuantity = parseInt(avQuantityInput.value);
    const quantity = parseInt(input.value);

    if (quantity > avQuantity) {
        // Set a custom error message for the input
        input.setCustomValidity("Quantity cannot be greater than Av Quantity!");
    } else {
        // Reset the error message if the validation passes
        input.setCustomValidity("");
    }
}

$(document).ready(function() {
    // Initialize select2 plugin for the first select element on page load
    const initialSelectElement = productsContainer.querySelector('.get_product');
});

initializeSelect2(initialSelectElement);

function initializeSelect2(element) {
    $(element).select2();
}

$(function() {
    $('#example-table').DataTable({
        pageLength: 10,
        //"ajax": './assets/demo/data/table_data.json',
        /*"columns": [
            { "data": "name" },
            { "data": "office" },
            { "data": "extn" },
            { "data": "start_date" },
            { "data": "salary" }
        ]*/
    });
})
