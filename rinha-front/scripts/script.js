$(document).ready(function() {

    alert('Deu')

    $.getJSON('http://127.0.0.1/User/getData/2', function(data, status) {
        console.log('dados: ', data)
    })


});
