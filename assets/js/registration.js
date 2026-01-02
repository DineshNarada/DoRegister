(function($){
    var key = 'doregister_form_state';

    function saveState(state){
        try{ localStorage.setItem(key, JSON.stringify(state)); }catch(e){}
    }

    function loadState(){
        try{ return JSON.parse(localStorage.getItem(key) || '{}'); }catch(e){return {};}
    }

    function collectData($form){
        var obj = {};
        $form.find('[name]').each(function(){
            var $el = $(this);
            var name = $el.attr('name');
            if ( name.indexOf('[]') !== -1 ) {
                var key = name.replace('[]','');
                obj[key] = obj[key] || [];
                if (this.checked) obj[key].push($el.val());
            } else if ($el.attr('type') === 'radio'){
                if (this.checked) obj[name] = $el.val();
            } else if ($el.attr('type') === 'file'){
                // file handling not implemented in this simple demo
            } else {
                obj[name] = $el.val();
            }
        });
        return obj;
    }

    function validateEmail(email){
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\\.,;:\s@\"]+\.)+[^<>()[\]\\.,;:\s@\"]{2,})$/i;
        return re.test(email);
    }

    function passwordScore(p){
        var score = 0;
        if (!p) return score;
        if (p.length >= 6) score += 1;
        if (p.length >= 8) score += 1;
        if (/[0-9]/.test(p)) score += 1;
        if (/[A-Z]/.test(p)) score += 1;
        if (/[^A-Za-z0-9]/.test(p)) score += 1;
        return score; // 0-5
    }

    function strengthText(score){
        if (score <= 1) return {text:'Very weak', pct:20, cls:'weak'};
        if (score == 2) return {text:'Weak', pct:40, cls:'weak'};
        if (score == 3) return {text:'Fair', pct:60, cls:'fair'};
        if (score == 4) return {text:'Good', pct:80, cls:'good'};
        return {text:'Strong', pct:100, cls:'strong'};
    }

    function showError(name, msg){
        var $err = $('.error[data-for="'+name+'"]');
        if ($err.length) $err.text(msg).show();
        else {
            var $field = $('[name="'+name+'"]');
            $field.after('<div class="error" data-for="'+name+'">'+msg+'</div>');
        }
    }

    function clearError(name){
        $('.error[data-for="'+name+'"]').text('').hide();
    }

    function validateStep($step){
        var ok = true;
        $step.find('[name]').each(function(){
            var $el = $(this);
            var name = $el.attr('name');
            clearError(name);

            if ($el.prop('required') && !$el.val()){
                showError(name, 'This field is required');
                ok = false;
                return;
            }

            if (name === 'email' && $el.val()){
                if (!validateEmail($el.val())){ showError(name, 'Invalid email'); ok = false; }
            }

            if (name === 'password' && $el.val()){
                if ($el.val().length < 6){ showError(name, 'Password too short (min 6)'); ok = false; }
            }

            if (name === 'confirm_password'){
                var pass = $step.closest('form').find('[name="password"]').val();
                if ($el.val() !== pass){ showError(name, 'Passwords do not match'); ok = false; }
            }
        });
        return ok;
    }

    $(function(){
        var $form = $('#doregister-form');
        if (!$form.length) return;

        var state = loadState();
        var $steps = $form.find('.step');
        var total = $steps.length;
        var current = state.currentStep || 1;

        function showStep(n){
            $steps.hide();
            $form.find('.step[data-step="'+n+'"]').show();
            var pct = Math.round((n-1)/(total-1)*100);
            $form.find('.aur-progress-line').css('width', 'calc(' + pct + '% - 60px)');
            $form.find('.aur-step').removeClass('active');
            $form.find('.aur-step[data-step="' + n + '"]').addClass('active');
            state.currentStep = n;
            saveState(state);
        }

        // restore values
        if (state.values){
            $.each(state.values, function(k,v){
                var $el = $form.find('[name="'+k+'"]');
                if (!$el.length) $el = $form.find('[name="'+k+'[]"]');
                if ($el.attr('type') === 'radio'){
                    $el.filter('[value="'+v+'"]').prop('checked', true);
                } else if ($el.attr('type') === 'checkbox'){
                    if (Array.isArray(v)){
                        v.forEach(function(val){ $el.filter('[value="'+val+'"]').prop('checked', true); });
                    } else {
                        $el.prop('checked', !!v);
                    }
                } else {
                    $el.val(v);
                }
            });
        }

        showStep(current);

        $form.on('click', '.next', function(){
            var $currentStepEl = $form.find('.step[data-step="'+(state.currentStep || 1)+'"]');
            if (!validateStep($currentStepEl)) return; // block progression on errors
            var n = Math.min(total, (state.currentStep || 1) + 1);
            showStep(n);
        });

        $form.on('click', '.back', function(){
            var n = Math.max(1, (state.currentStep || 1) - 1);
            showStep(n);
        });

        $form.on('change keyup', '[name]', function(e){
            var $el = $(this);
            var name = $el.attr('name');
            clearError(name);

            // password strength handling
            if (name === 'password'){
                var val = $el.val();
                var score = passwordScore(val);
                var info = strengthText(score);
                var $meter = $('.password-strength[data-for="password"]');
                if ($meter.length){
                    $meter.find('.strength-bar').css('width', info.pct + '%').removeClass('weak fair good strong').addClass(info.cls);
                    $meter.find('.strength-text').text(info.text);
                }
            }

            // handle photo file input change (upload via AJAX)
            if ($el.attr('type') === 'file' && $el.attr('name') === 'photo' && $el[0].files && $el[0].files[0]){
                var file = $el[0].files[0];
                var fd = new FormData();
                fd.append('action', 'doregister_upload');
                fd.append('nonce', DrAjax.upload_nonce);
                fd.append('photo', file);

                $.ajax({
                    url: DrAjax.ajax_url,
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(resp){
                        if (resp.success){
                            // set hidden photo_id and preview
                            $form.find('[name="photo_id"]').val(resp.data.attachment_id);
                            state.values = state.values || {};
                            state.values.photo_id = resp.data.attachment_id;
                            saveState(state);
                            if (resp.data.url){
                                var $img = $form.find('.photo-preview');
                                $img.attr('src', resp.data.url).show();
                            }
                        } else {
                            showError('photo', resp.data && resp.data.message ? resp.data.message : 'Upload failed');
                        }
                    },
                    error: function(){ showError('photo', 'Upload request failed'); }
                });
            }

            var values = collectData($form);
            state.values = values;
            saveState(state);
        });

        $form.on('submit', function(e){
            e.preventDefault();
            var data = collectData($form);
            $.post(DrAjax.ajax_url, {
                action: 'doregister_register',
                nonce: DrAjax.nonce,
                data: data
            }, function(resp){
                if (resp.success){
                    localStorage.removeItem(key);
                    alert(resp.data.message || 'Registered');
                    window.location = resp.data.redirect || window.location.href;
                } else {
                    alert(resp.data && resp.data.message ? resp.data.message : 'Error');
                }
            }).fail(function(){ alert('Request failed'); });
        });
    });
})(jQuery);
