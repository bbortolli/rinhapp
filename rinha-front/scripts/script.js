$(document).ready(function() {

    $('#loginBtn').on('click', function(e) {
        e.preventDefault()
        let user = $('#formUser').val()
        let pass = $('#formPass').val()
        let data = JSON.stringify({nickname: user, password: pass})

        $.ajax({
            url: 'http://127.0.0.1/rinha-api/User/login',
            type: 'POST',
            data,
            dataType: 'json',
            success: function (data) {
                if (data.token) {
                    localStorage.setItem('usertoken', 'token')
                    window.location.replace('./user.html')
                    console.clear();   
                }
                else {
                    console.log("invalid")
                }
            }
        });
    });

    let token = 1;
    if(token) {
        $.get('http://127.0.0.1/rinha-api/Rinha/getAll', function(data, status) {
            $.each(data, function(index, value) {
                var id = $('<p></p>').text(value._id)
                var team1 = $('<p></p>').text(value.team1.toUpperCase())
                var team2 = $('<p></p>').text(value.team2.toUpperCase())
                var endtime = $('<p></p>').text('Ends: '+ value.endtime)
                var totalteam1 = $('<p></p>').text(value.totalteam1)
                var totalteam2 = $('<p></p>').text(value.totalteam2)
                $('.games').append('<div class="game index-'+index+'">')
                $('.game.index-'+index).append('<div class="res results-'+index+'">')
                $('.game.index-'+index).append('<div class="inf infos-'+index+'">')
                $('.results-'+index).append('<div class="t1dat t1c-'+index+'">')
                $('.results-'+index).append('<div class="t2dat t2c-'+index+'">')
                $('.infos-'+index).append(id, endtime)
                var btnVote = $('<button class="voteBtn" [gameid]='+value._id+' [tid]="first"></button>').text('Vote!')
                $('.t1c-'+index).append(team1, totalteam1, btnVote)
                var btnVote = $('<button class="voteBtn" [gameid]='+value._id+' [tid]="secnd"></button>').text('Vote!')
                $('.t2c-'+index).append(team2, totalteam2, btnVote)
            })
            $('.voteBtn').on('click', function(e) {
                button = $(e.target)
                let rinhaid = button[0].attributes[1].nodeValue
                let teamvoted = button[0].attributes[2].nodeValue
                vote = JSON.stringify({'userid': 2, rinhaid , teamvoted})
                
                $.ajax({
                    url: 'http://127.0.0.1/rinha-api/Vote/addData',
                    type: 'POST',
                    data: vote,
                    dataType: 'json',
                    success: function (data) {
                        if (data.message == "Can't create data") {
                            alert('Já votou!')
                            console.clear();   
                        }
                        else {
                            let previous = button.prev()
                            previous.text( parseInt(previous.text())+1 )
                            console.clear();   
                        }    
                    }
                });
            })
        });
    }

    $('#logoutbtn').on('click', function() {
        localStorage.removeItem('usertoken')
        window.location.replace('./index.html')
    })
});
