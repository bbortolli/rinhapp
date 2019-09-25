$(document).ready(function() {

    // Login button event handler

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
            success: function (data, status) {
                if (status == 'success') {
                    console.log(data.time)
                    localStorage.setItem('usertoken', data.token)
                    window.location.replace('./index.html')
                }
                else {
                    console.log("invalid")
                }
            },
            error: function() {
                console.log('aqui error')
            }
        });
    });

    // API Request: Logged user rinhas

    if(window.location.pathname === '/rinha-front/public/rinhas.html') {
        $.ajax({
            url: 'http://127.0.0.1/rinha-api/Rinha/getByUser',
            type: 'GET',
            headers: {
                'Authorization' : localStorage.getItem('usertoken')
            },
            contentType: 'text/plain',
            success: function (data) {
                data.forEach(function(value, index) {
                    var id = $('<p></p>').text(value._id)
                    var team1 = $('<p></p>').text(value.team1.toUpperCase())
                    var team2 = $('<p></p>').text(value.team2.toUpperCase())
                    var endtime = $('<p></p>').text('Ends: '+ value.endtime)
                    var delbtn = $('<i class="fas fa-trash delBtn" ident="'+value._id+'">')
                    var totalteam1 = $('<p></p>').text(value.totalteam1)
                    var totalteam2 = $('<p></p>').text(value.totalteam2)
                    $('.owned').append('<div class="game index-'+index+'">')
                    $('.game.index-'+index).append('<div class="res results-'+index+'">')
                    $('.game.index-'+index).append('<div class="inf infos-'+index+'">')
                    $('.results-'+index).append('<div class="t1dat t1c-'+index+'">')
                    $('.results-'+index).append('<div class="t2dat t2c-'+index+'">')
                    $('.infos-'+index).append(id, endtime, delbtn)
                    var btnVote = $('<button class="voteBtn" gameid='+value._id+' tid="first"></button>').text('Vote!')
                    $('.t1c-'+index).append(team1, totalteam1, btnVote)
                    var btnVote = $('<button class="voteBtn" gameid='+value._id+' tid="secnd"></button>').text('Vote!')
                    $('.t2c-'+index).append(team2, totalteam2, btnVote)
                })

                $('.voteBtn').on('click', function(e) {
                    button = $(e.target)
                    let rinhaid = button[0].attributes[1].nodeValue
                    let teamvoted = button[0].attributes[2].nodeValue
                    vote = JSON.stringify({rinhaid , teamvoted})
                    
                    $.ajax({
                        url: 'http://127.0.0.1/rinha-api/Vote/addData',
                        type: 'POST',
                        headers: {
                            'Authorization' : localStorage.getItem('usertoken')
                        },
                        data: vote,
                        contentType: 'text/plain',
                        success: function (data) {
                            if (data.message == "Can't create data") {
                                alert('Já votou!')
                            }
                            else {
                                let previous = button.prev()
                                previous.text( parseInt(previous.text())+1 )
                            }    
                        }
                    });
                    location.reload()
                })

                $('.delBtn').on('click', function(e) {

                    let delid = e.target.getAttribute('ident')
                    console.log(delid)
                    $.ajax({
                        url: 'http://127.0.0.1/rinha-api/Rinha/removeData',
                        type: 'DELETE',
                        headers: {
                            'Authorization' : localStorage.getItem('usertoken')
                        },
                        data: vote,
                        contentType: 'text/plain',
                        success: function (data) {
                            if (data.message == "Can't create data") {
                                alert('Já votou!')
                            }
                            else {
                                let previous = button.prev()
                                previous.text( parseInt(previous.text())+1 )
                            }    
                        }
                    });
                    location.reload()
                })
            },
            complete: function() {
                $.ajax({
                    url: 'http://127.0.0.1/rinha-api/Vote/getAll',
                    type: 'GET',
                    headers: {
                        'Authorization' : localStorage.getItem('usertoken')
                    },
                    contentType: 'text/plain',
                    success: function (data) {
                        data.forEach( function(value) {
                            let idhide = value.rinhaid
                            let team = value.teamvoted
                            $('button[gameid="'+idhide+'"]').attr('disabled', true)
                            $('button[gameid="'+idhide+'"]').text('#')
                            $('[gameid='+idhide+'][tid='+team+']').text('Your choice!')
                            $('[gameid='+idhide+'][tid='+team+']').parent().addClass('greenbg')
                        });
                    }
                });
            }
        })

    }

    // API Request: Rinhas that user voted

    if(window.location.pathname === '/rinha-front/public/votes.html') {
        $.ajax({
            url: 'http://127.0.0.1/rinha-api/Rinha/getAllVoted',
            type: 'GET',
            headers: {
                'Authorization' : localStorage.getItem('usertoken')
            },
            contentType: 'text/plain',
            success: function (data) {
                data.forEach( function(value, index) {
                    var id = $('<p></p>').text(value._id)
                    var team1 = $('<p></p>').text(value.team1.toUpperCase())
                    var team2 = $('<p></p>').text(value.team2.toUpperCase())
                    var endtime = $('<p></p>').text('Ends: '+ value.endtime)
                    var delbtn = $('<i class="fas fa-trash delBtn" ident="'+value._id+'">')
                    var totalteam1 = $('<p></p>').text(value.totalteam1)
                    var totalteam2 = $('<p></p>').text(value.totalteam2)
                    $('.votes').append('<div class="game index-'+index+'">')
                    $('.game.index-'+index).append('<div class="res results-'+index+'">')
                    $('.game.index-'+index).append('<div class="inf infos-'+index+'">')
                    $('.results-'+index).append('<div class="t1dat t1c-'+index+'">')
                    $('.results-'+index).append('<div class="t2dat t2c-'+index+'">')
                    $('.infos-'+index).append(id, endtime, delbtn)
                    var btnVote = $('<button class="voteBtn" gameid='+value._id+' tid="first"></button>').text('Vote!')
                    $('.t1c-'+index).append(team1, totalteam1, btnVote)
                    var btnVote = $('<button class="voteBtn" gameid='+value._id+' tid="secnd"></button>').text('Vote!')
                    $('.t2c-'+index).append(team2, totalteam2, btnVote)

                });
            },
            complete: function() {
                $.ajax({
                    url: 'http://127.0.0.1/rinha-api/Vote/getAll',
                    type: 'GET',
                    headers: {
                        'Authorization' : localStorage.getItem('usertoken')
                    },
                    contentType: 'text/plain',
                    success: function (data) {
                        data.forEach( function(value) {
                            let idhide = value.rinhaid
                            let team = value.teamvoted
                            $('button[gameid="'+idhide+'"]').attr('disabled', true)
                            $('button[gameid="'+idhide+'"]').text('#')
                            $('[gameid='+idhide+'][tid='+team+']').text('Your choice!')
                            $('[gameid='+idhide+'][tid='+team+']').parent().addClass('greenbg')
                        });
                    }
                });
            }
        });
    }

    // Logout button handler

    $('#logoutBtn').on('click', function() {
        localStorage.removeItem('usertoken')
        window.location.replace('./login.html')
    })

    // Register button 

    $('#registerBtn').on('click', function(e) {
        e.preventDefault()
        let nickname = $('#formUser').val()
        let password = $('#formPass').val()
        let repass = $('#formRePass').val()
        let email = $('#formEmail').val()
        let remail = $('#formReEmail').val()
        let data = JSON.stringify({nickname, password, email})

        if(password !== '' && password === repass && email !== '' && email === remail) {
            $.ajax({
                url: 'http://127.0.0.1/rinha-api/User/addData',
                type: 'POST',
                data,
                dataType: 'json',
                success: function (data) {
                    if(data.message === 'Data created') {
                        window.location.replace('./login.html')
                        alert('Thank you for registering!')
                    }
                    else {
                        alert('Cant register, try again!')
                    }
                },
                error: function(req, status, err) {
                    console.log('Status: ', status, 'Erro: ', err)
                }
            });
        }
    });

    // Input handlers and messages

    $('#formRePass').keyup(function () {
        let pass = $('#formPass').val()
        let repass = $(this).val()
        if (pass !== repass) {
            $('#pass-warn').text('Password dont match')
        }
        else {
            $('#pass-warn').text('')
        }
      });

      $('#formReEmail').keyup(function () {
        let email = $('#formEmail').val()
        let reemail = $(this).val()
        if (email !== reemail) {
            $('#email-warn').text('Email dont match')
        }
        else {
            $('#email-warn').text('')
        }
      });

    // Add rinha button handler

    $('#addBtn').on('click', function(e) {
        e.preventDefault()
        let team1 = $('#t1add').val()
        let team2 = $('#t2add').val()
        let endtime = $('#rdate').val()
        let token = localStorage.getItem('usertoken')
        
        team1 = team1.replace(/[^a-zA-Z0-9 ]*/g, '')
        team1 = team1.replace(/\s\s+/g, ' ')

        team2 = team2.replace(/[^a-zA-Z0-9 ]*/g, '')
        team2 = team2.replace(/\s\s+/g, ' ')

        let data = JSON.stringify({team1, team2, endtime, token})

        if(team1 !== '' && team2 !== '' && endtime !== '') {
            $.ajax({
                url: 'http://127.0.0.1/rinha-api/Rinha/addData',
                type: 'POST',
                data,
                dataType: 'json',
                success: function (data) {
                    console.log(data)
                }
            });
        }

        location.reload();
    });

    // Function Shuffle array

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    if(window.location.pathname === '/rinha-front/public/index.html') {
        $.ajax({
            url: 'http://127.0.0.1/rinha-api/Rinha/getAll',
            type: 'GET',
            headers: {
                'Authorization' : localStorage.getItem('usertoken')
            },
            contentType: 'text/plain',
            success: function (data) {
                shuffleArray(data)
                data.forEach( function(value, index) {
                    var id = $('<p></p>').text(value._id)
                    var team1 = $('<p></p>').text(value.team1.toUpperCase())
                    var team2 = $('<p></p>').text(value.team2.toUpperCase())
                    var endtime = $('<p></p>').text('Ends: '+ value.endtime)
                    var totalteam1 = $('<p></p>').text(value.totalteam1)
                    var totalteam2 = $('<p></p>').text(value.totalteam2)
                    $('.geral').append('<div class="col-md-6 col-xs-3 game index-'+index+'">')
                    $('.game.index-'+index).append('<div class="res results-'+index+'">')
                    $('.game.index-'+index).append('<div class="inf infos-'+index+'">')
                    $('.results-'+index).append('<div class="t1dat t1c-'+index+'">')
                    $('.results-'+index).append('<div class="t2dat t2c-'+index+'">')
                    $('.infos-'+index).append(id, endtime)
                    var btnVote = $('<button class="voteBtn" gameid='+value._id+' tid="first"></button>').text('Vote!')
                    $('.t1c-'+index).append(team1, totalteam1, btnVote)
                    var btnVote = $('<button class="voteBtn" gameid='+value._id+' tid="secnd"></button>').text('Vote!')
                    $('.t2c-'+index).append(team2, totalteam2, btnVote)

                });
            },
            complete: function() {
                $('.voteBtn').on('click', function(e) {
                    button = $(e.target)
                    let rinhaid = button[0].attributes[1].nodeValue
                    let teamvoted = button[0].attributes[2].nodeValue
                    vote = JSON.stringify({rinhaid , teamvoted})
                    
                    $.ajax({
                        url: 'http://127.0.0.1/rinha-api/Vote/addData',
                        type: 'POST',
                        headers: {
                            'Authorization' : localStorage.getItem('usertoken')
                        },
                        data: vote,
                        contentType: 'text/plain',
                        success: function (data) {
                            if (data.message == "Can't create data") {
                                alert('Já votou!')
                            }
                            else {
                                let previous = button.prev()
                                previous.text( parseInt(previous.text())+1 )
                            }    
                        }
                    });
                    location.reload()
                })

                $.ajax({
                    url: 'http://127.0.0.1/rinha-api/Vote/getAll',
                    type: 'GET',
                    headers: {
                        'Authorization' : localStorage.getItem('usertoken')
                    },
                    contentType: 'text/plain',
                    success: function (data) {
                        data.forEach( function(value) {
                            let idhide = value.rinhaid
                            let team = value.teamvoted
                            $('button[gameid="'+idhide+'"]').attr('disabled', true)
                            $('button[gameid="'+idhide+'"]').text('#')
                            $('[gameid='+idhide+'][tid='+team+']').text('Your choice!')
                            $('[gameid='+idhide+'][tid='+team+']').parent().addClass('greenbg')
                        });
                    }
                });
            }
        })
    }
});
