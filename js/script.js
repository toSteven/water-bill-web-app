let btn = document.querySelector("#btn");
let siderbar = document.querySelector(".sidebar");
let searchBtn = document.querySelector(".bx-search");
btn.onclick = function() {
    siderbar.classList.toggle("active");
}
searchBtn.onclick = function() {
    siderbar.classList.toggle("active");
}

$(document).ready(function () {
    $('#client-data-table').DataTable({
        scrollY: '400px',
        scrollCollapse: true,
        paging: true,
    });
    $('.dataTables_length').addClass('bs-select');
});

//Search Bar Live Search
$(document).ready(function(){
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("function/search-config.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });
    
    // Set search input value on click of result item
    $(document).on("click", ".result p", function(){
        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        $(this).parent(".result").empty();
    });
});

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}