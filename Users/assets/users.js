(function() {
    // Definícia funkcie, ktorá sa má spustiť
    var runMe = function($dotapp) {

        // Registracny formular

        function registration_form($dotapp) {

            $dotapp('#registration INPUT[name="email"]').on("keyup", function() {
                $dotapp('#error-message').removeClass("visible");
                if ($dotapp().validator.isEmail($dotapp(this).val())) {
                    $dotapp(this).removeClass("bad").removeClass("good").addClass("ready");
                } else {
                    $dotapp(this).addClass("bad").removeClass("good").removeClass("ready");
                }
            });

            $dotapp('#registration INPUT[name="password"]').on("keyup", function() {
                $dotapp('#error-message').removeClass("visible");
                if ($dotapp().validator.isStrongPassword($dotapp(this).val())) {
                    $dotapp(this).removeClass("bad").removeClass("good").addClass("ready");
                } else {
                    $dotapp(this).addClass("bad").removeClass("good").removeClass("ready");
                }
            });

            $dotapp()
                .form('#registration')
                .before((data, form) => {
                    $dotapp('#error-message').removeClass("visible");
                    if ($dotapp(form).attr("blocked") == 1) {
                        return $dotapp().halt();
                    }
                    $dotapp(form).attr("blocked","1");
                    $dotapp("#registrationbtn").addClass("loading");
                })
                .after((data, response, form) => {
                    $dotapp(form).attr("blocked","0");
                    $dotapp("#registrationbtn").removeClass("loading");
                    if (reply = $dotapp().parseReply(response)) {
                        if (reply.status == 1) {
                            $dotapp('#registration INPUT[name="email"]').removeClass("bad").removeClass("good").removeClass("ready").val("");
                            $dotapp('#registration INPUT[name="password"]').removeClass("bad").removeClass("good").removeClass("ready").val("");
                            alert(reply.message);
                            window.location = reply.redirectTo;
                        } else {
                            if (reply.errorNo == 1) {
                                $dotapp('#registration INPUT[name="email"]').addClass("bad").removeClass("good").removeClass("ready");
                                $dotapp('#error-message').addClass("visible").html(reply.message);
                            }
                            if (reply.errorNo == 2) {
                                $dotapp('#registration INPUT[name="password"]').addClass("bad").removeClass("good").removeClass("ready");
                                $dotapp('#error-message').addClass("visible").html(reply.message);
                            }
                            if (reply.errorNo == 3) {
                                $dotapp('#registration INPUT[name="email"]').addClass("bad").removeClass("good").removeClass("ready");
                                $dotapp('#error-message').addClass("visible").html(reply.message);
                            }
                            if (reply.errorNo == 4) {
                                $dotapp('#error-message').addClass("visible").html(reply.message);
                            }
                        }
                    }
                });

        }

        function login_form($dotapp) {

            $dotapp('#login INPUT[name="email"]').on("keyup", function() {
                $dotapp('#error-message').removeClass("visible");
                if ($dotapp().validator.isEmail($dotapp(this).val())) {
                    $dotapp(this).removeClass("bad").removeClass("good").addClass("ready");
                } else {
                    $dotapp(this).addClass("bad").removeClass("good").removeClass("ready");
                }
            });

            $dotapp('#login INPUT[name="password"]').on("keyup", function() {
                $dotapp('#error-message').removeClass("visible");
                if ($dotapp().validator.isStrongPassword($dotapp(this).val())) {
                    $dotapp(this).removeClass("bad").removeClass("good").addClass("ready");
                } else {
                    $dotapp(this).addClass("bad").removeClass("good").removeClass("ready");
                }
            });

            $dotapp()
                .form('#login')
                .before((data, form) => {
                    $dotapp('#error-message').removeClass("visible");
                    if ($dotapp(form).attr("blocked") == 1) {
                        return $dotapp().halt();
                    }
                    $dotapp(form).attr("blocked","1");
                    $dotapp("#loginbtn").addClass("loading");
                })
                .after((data, response, form) => {
                    if (reply = $dotapp().parseReply(response)) {
                        if (reply.status == 1) {
                            $dotapp('#login INPUT[name="email"]').removeClass("bad").removeClass("good").removeClass("ready").val("");
                            $dotapp('#login INPUT[name="password"]').removeClass("bad").removeClass("good").removeClass("ready").val("");
                            window.location = reply.redirectTo;
                        } else {
                            if (reply.errorNo == 1) {
                                $dotapp('#login INPUT[name="email"]').addClass("bad").removeClass("good").removeClass("ready");
                                $dotapp('#error-message').addClass("visible").html(reply.message);
                            }
                            if (reply.errorNo == 2) {
                                $dotapp('#login INPUT[name="email"]').addClass("bad").removeClass("good").removeClass("ready");
                                $dotapp('#login INPUT[name="password"]').addClass("bad").removeClass("good").removeClass("ready");
                                $dotapp('#error-message').addClass("visible").html(reply.message);
                            }
                            $dotapp(form).attr("blocked","0");
                            $dotapp("#loginbtn").removeClass("loading");
                        }
                    } else {
                        $dotapp(form).attr("blocked","0");
                        $dotapp("#loginbtn").removeClass("loading");
                    }
                });
        }

        function tfa($dotapp) {

            window.setTimeout(function() {
                $dotapp('#first').focus();
            },200);

            $dotapp(".two-fa-inputs INPUT").twoFactor((code) => {
                $dotapp("DIV.two-fa-inputs")
                    .removeClass("bad")
                    .addClass("ready")
                    .removeClass("good");
                $dotapp('#twofaform INPUT[name="code"]').val(code);
                $dotapp('#error-message').removeClass("visible");
                $dotapp("#twofaform").submit();
            }, {
                allowLetters: false,
                uppercase: true,
                autoSubmit: true,
                invalidClass: 'error'
            });

            //if (reply = $dotapp().parseReply(data))
            $dotapp()
                .form('#twofaform')
                .before((data, form) => {
                    var inputy = $dotapp('#twofaform .two-fa-inputs INPUT');
                    $dotapp('#twofaform .two-fa-inputs INPUT').attr("disabled","1").val("*");
                    $dotapp("#confirm2fa").addClass("loading");
                    if ($dotapp(form).attr("blocked") == 1) {
                        return $dotapp().halt();
                    }
                    $dotapp(form).attr("blocked","1");                    
                })
                .after((data, response, form) => {
                    if (reply = $dotapp().parseReply(response)) {
                        if (reply.status == 1) {
                            $dotapp("DIV.two-fa-inputs").removeClass("bad").removeClass("ready").addClass("good");
                            window.location = reply.redirectTo;
                        } else {
                            $dotapp('#twofaform .two-fa-inputs INPUT').removeAttr("disabled").val("");
                            $dotapp("#confirm2fa").removeClass("loading");
                            $dotapp("DIV.two-fa-inputs").addClass("bad").removeClass("ready").removeClass("good");
                            $dotapp(form).attr("blocked","0");
                            $dotapp("#confirm2fa").removeClass("loading");
                            if (reply.errorNo == 1) {
                                $dotapp('#error-message').addClass("visible").html(reply.message);
                            }
                            window.setTimeout(function() {
                                $dotapp('#first').focus();
                            },200);
                        }
                    } else {
                        $dotapp('#twofaform .two-fa-inputs INPUT').removeAttr("disabled").val("");
                        $dotapp("DIV.two-fa-inputs").removeClass("bad").removeClass("ready").removeClass("good");
                        $dotapp(form).attr("blocked","0");
                        $dotapp("#confirm2fa").removeClass("loading");
                        window.setTimeout(function() {
                            $dotapp('#first').focus();
                        },200);
                    }
                });

            $dotapp("#confirm2fa").on("click", function() {
                $dotapp('#error-message').removeClass("visible");
                const code2fa = $dotapp(".two-fa-inputs INPUT").twoFactor();
                if (code2fa === false) {
                    $dotapp("DIV.two-fa-inputs").addClass("bad").removeClass("ready").removeClass("good");
                    $dotapp('#error-message').addClass("visible").html("Invalid code format !");
                } else {
                    $dotapp('#twofaform INPUT[name="code"]').val(code2fa);
                    $dotapp("DIV.two-fa-inputs").removeClass("bad").removeClass("good").addClass("ready");
                    $dotapp("#twofaform").submit();
                }
            });

            $dotapp("DIV.two-fa-inputs INPUT").on("keyup", function() {
                $dotapp('#error-message').removeClass("visible");
                const code2fa = $dotapp(".two-fa-inputs INPUT").twoFactor();
                if (code2fa === false) {
                    $dotapp("DIV.two-fa-inputs").removeClass("bad").removeClass("ready").removeClass("good");
                }
            });

        }


        registration_form($dotapp);
        login_form($dotapp);
        tfa($dotapp);

    };

    if (window.$dotapp) {
        runMe(window.$dotapp);
    } else {
        window.addEventListener('dotapp', function() {
            runMe(window.$dotapp);
        }, { once: true });
    }
})();