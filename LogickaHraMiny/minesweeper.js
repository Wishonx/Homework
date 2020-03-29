function generateField(rows, cols) {
    for (x = 0; x < rows; x++) {
        document.getElementById("play_field").innerHTML += "<div class='row "+x+"'></div>";

        for (y = 0; y < cols; y++) {

            var $rnd = Math.floor(Math.random() * 101);

            if ($rnd <= 10) {
                document.getElementById("play_field").innerHTML += "<div class='col bomb hidden "+y+"'></div>";
            } else {
                document.getElementById("play_field").innerHTML += "<div class='col hidden "+y+"'></div>";
            }
        }
    }
}


$(function () {
    $(".col").on("click", function () {
        const $cell = $(this);
        $($cell).removeClass('hidden');
        if($cell.hasClass('bomb')){
        alert('Game Over');
        $('.col').removeClass('hidden');
        }
    });
    
})

generateField(10, 10);