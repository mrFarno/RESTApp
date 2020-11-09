function reset_password() {
    var email = document.getElementById('reset')
    if (email.value.trim() !== '') {
        $.ajax({
            url : 'index.php?page=login',
            type : 'POST',
            data : 'reset='+email.value,
            dataType : 'json',
            success: function(data) {
                show_toast(data[0], data[1])
            }
        })
    }
}

function add_autofocus() {
    var modal = EventTarget.getAttribute("data-target")
    $(modal).on('shown.bs.modal', function () {
        $(modal).find('input').first().trigger('focus')
    })
}

function delete_confirm() {
    return confirm('Etes vous sûr de vouloir supprimer cet utilisateur ?')
}

function modal_set_action(action, param) {
    var btn = document.getElementById('modal_role_btn')
    btn.name = action
    btn.value = param

    label_element = document.getElementById('action_label')
    edit_label = document.getElementById('modal_label')
    edit_mail = document.getElementById('modal_mail')
    edit_login = document.getElementById('modal_login')
    label_txt = ''
    if (action == 'add') {
        label_txt = 'Ajout d\'un compte'
        if (param == 'Admin') {
            label_txt = label_txt+' administrateur'
        } else {
            label_txt = label_txt+' contributeur'
        }
        edit_label.value = ''
        edit_mail.value = ''
        edit_login.value = ''
    } else {
        label_txt = 'Modification du compte '
        $.ajax({
            url : 'index.php?page=accounts',
            type : 'POST',
            data : 'find='+param,
            dataType : 'json',
            success: function(data) {
                label_txt = label_txt+data.label
                edit_label.value = data.label            
                edit_mail.value = data.mail
                edit_login.value = data.login
                label_element.innerHTML=label_txt
            }
        })
    }
    label_element.innerHTML=label_txt
    modal = document.getElementById('user_modal')
    $(modal).find('input').first().trigger('focus')
}

function get_user_infos(id) {
    $.ajax({
        url : 'index.php?page=accounts',
        type : 'POST',
        data : 'find='+id,
        dataType : 'json',
        success: function(data) {
            edit_label = document.getElementById('edit_label')
            edit_label.value = data.label            
            edit_mail = document.getElementById('edit_mail')
            edit_mail.value = data.mail           
            edit_role = document.getElementById('edit_'+data.role)
            edit_role.selected=true
        }
    })
}

function valid_form() {
    input1 = document.getElementById('u_firstname')
    input2 = document.getElementById('u_lastname')
    input3 = document.getElementById('u_email')

    input1.required = false
    input2.required = false
    input3.required = false
}

function update_current_rest() {
    form = document.getElementById('current-rest-form')
    form.submit()
}

function update_current_meal() {
    hidden = document.getElementById('current-meal')
    hidden.value = event.target.value

    form = document.getElementById('meal-form')
    form.submit()
}

function modal_init(u_id) {
    u_input = document.getElementById('u_id')
    u_input.value = u_id
    firstname = document.getElementById('firstname-'+u_id).innerHTML
    lastname = document.getElementById('lastname-'+u_id).innerHTML

    span1 = document.getElementById('u_firstname')
    span1.innerHTML = firstname
    span2 = document.getElementById('u_lastname')
    span2.innerHTML = lastname
    $.ajax({
        url : 'index.php?page=affectations',
        type : 'POST',
        data : 'findaf_uid='+u_id,
        dataType : 'json',
        success: function(data) {
            for (let i = 1; i < 5; i++) {
                $('#mt-'+i).prop('checked', false)
                $('#start-mt-'+i).attr('hidden', true)
                $('#end-mt-'+i).attr('hidden', true)
            }
            for (var i in data) {
                check = $('#mt-'+data[i].af_meal_type)
                console.log(check)
                check.prop('checked', true)
                start = document.getElementById('af_timestart-'+data[i].af_meal_type)
                $('#start-mt-'+data[i].af_meal_type).removeAttr('hidden')
                start.hidden = false
                start.value = data[i].af_timestart.split(' ')[0]
                end = document.getElementById('af_timeend-'+data[i].af_meal_type)
                $('#end-mt-'+data[i].af_meal_type).removeAttr('hidden')

                end.hidden = false
                if (data[i].af_timeend !== null) {
                    end.value = data[i].af_timeend.split(' ')[0]
                }
            }
        }
    })
}

function display_dates(mt_id) {
    start = document.getElementById('start-mt-'+mt_id)
    end = document.getElementById('end-mt-'+mt_id)

    if (start.hidden === true) {
        start.hidden = false
        end.hidden = false
    } else {
        start.hidden = true
        end.hidden = true
    }
}

function load_form(form, page) {
    comment_hidden = document.getElementById('check-step')
    if (comment_hidden !== null) {
        comment_hidden.value = form
    }
    current_meal = document.getElementById('current-meal')
    date = document.getElementById('current-date')

    $(".btn-active").removeClass("btn-active");
    $("#"+form+"-btn").addClass('btn-active')
    if (date !== null && current_meal !== null) {
        form = form + '&date='+date.value+'&current-meal='+current_meal.value
    }
    $.ajax({
        url : 'index.php?page='+page,
        type : 'POST',
        data : 'form='+form,
        dataType : 'html',
        success: function(data) {
            container = document.getElementById('form-container')
            container.innerHTML = data
        }
    })
}

function post_form(form, page) {
    data = {}
    data['validform'] = form
    current_meal = document.getElementById('current-meal')
    if (current_meal !== null) {
        data['current-meal'] = current_meal.value
    }
    date = document.getElementById('current-date')
    if (date !== null) {
        data['date'] = date.value
    }
    // console.log(inputs)
    form = document.getElementById('step-form')
    formdata = new FormData(form)
    for (var [key, value] of formdata.entries()) {
        data[key] = value
    }
    $.ajax({
        url : 'index.php?page='+page,
        type : 'POST',
        data : data,
        success: function() {
            show_toast('success', 'Mise à jour enregistrée')
        }
    })
}

function update_stock(kit_part) {
    if (kit_part == 1) {
        var inputs = $('.missing-input.kit-part-target')
        var missing = 0
        for (let i = 0; i < inputs.length; i++) {
            if (Number(inputs[i].value) > Number(missing)) {
                missing = Number(inputs[i].value)
            }
        }
        var stocks = $('.kit-part-target')
        var input = document.getElementById('kit-nmbr')
        for (let i = 0; i < stocks.length; i++) {
            stocks[i].innerHTML = input.value - Number(missing)
        }
    } else {
        id = event.target.id.replace('missing-', '')
        stock = document.getElementById('stock-'+id)
        var missing = event.target.value
        remain = document.getElementById(id+'-stock')
        console.log(remain)
        stock.innerHTML = remain.value-missing
    }
}

function show_absence_button(id) {
    btn = document.getElementById('absence-'+id)
    check = document.getElementById(id+'-present')
    console.log(check)
    if (btn.hidden === true) {
        btn.hidden = false
        check.checked = false;
    } else {
        btn.hidden = true
        check.checked = true;
    }
}

function show_infos_button(id) {
    btn = document.getElementById('failure-'+id)
    if (btn.hidden === true) {
        btn.hidden = false
    } else {
        btn.hidden = true
    }
}

function submit_absences_form() {
    form = document.getElementById('absences-form')
    form.submit()
}

function update_user_id() {
    id = event.target.id.replace('absence-', '')
    hidden = document.getElementById('ab_user_id')
    hidden.value = id
}

function save_comment() {
    data = {}
    step = document.getElementById('check-step')
    comment = document.getElementById('comment-content')
    meal = document.getElementById('meal_id')
    data = {
        'meal' : meal.value,
        'step' : step.value,
        'content' : comment.value,
    }
    $.ajax({
        url : 'index.php?page=comments',
        type : 'POST',
        data : data,
        dataType : 'json',
        success: function(data) {
            show_toast(data[0], data[1])
        }
    })
}

function init_comment_modal() {
    data = {}
    step = document.getElementById('check-step')
    meal = document.getElementById('meal_id')
    data = {
        'prefill' : meal.value,
        'step' : step.value,
    }
    $.ajax({
        url : 'index.php?page=comments',
        type : 'POST',
        data : data,
        dataType : 'text',
        success: function(data) {
            comment = document.getElementById('comment-content')
            comment.value = data
            comment.focus()
        }
    })
}

function get_equipment_infos(eq_id) {
    $.ajax({
        url : 'index.php?page=equipment',
        type : 'POST',
        data : 'search='+eq_id,
        dataType : 'json',
        success: function(data) {
            contact = document.getElementById('eq_contact')
            instructions = document.getElementById('eq_instructions')

            contact.innerText = data.eq_fail_contact
            instructions.innerText = data.eq_fail_instructions
        }
    })
}

function update_eq_stock(eq_id, table) {
    stock = document.getElementById(eq_id+'-stock')
    $.ajax({
        url : 'index.php?page=equipment',
        type : 'POST',
        data : 'update='+eq_id+'&type='+table+'&stock='+stock.value,
        success: function() {
            show_toast('success', 'Mise à jour réussie')
        }
    })
}

function update_failed_eq(id) {
    $.ajax({
        url : 'index.php?page=equipment',
        type : 'POST',
        data : 'failed='+id,
        success: function() {
            show_toast('success', 'Mise à jour réussie')
        }
    })
}

function delete_eq(id, table) {
    if(confirm('Etes vous sur de vouloir supprimer cet équipement ?')) {
        $.ajax({
            url : 'index.php?page=equipment',
            type : 'POST',
            data : 'delete='+id+'&type='+table,
            success: function() {
                show_toast('success', 'Suppression réussie')
            }
        })
    }
}

function init_cleaning_modal(id) {
    hidden = document.getElementById('t_target_id')
    hidden.value = id

    select = document.getElementById('t_user_id')
    ul = document.getElementById('responsibles')

    ul.innerHTML = ''
    select.innerHTML = ''

    selected = document.createElement('option')
    selected.selected = true
    selected.disabled = true
    selected.innerHTML='---Ajouter un responsable---'
    select.appendChild(selected)

    $.ajax({
        url : 'index.php?page=cleaning',
        type : 'POST',
        data : 'search='+id,
        dataType : 'json',
        success: function(data) {
            for (var key in data.employees) {
                console.log(key, data.employees[key])
                let option = document.createElement('option')
                option.value = key
                option.innerHTML = data.employees[key]
                select.appendChild(option)
            }
            for (var keyy in data.responsibles) {
                let li = document.createElement('li')
                li.innerHTML = data.responsibles[keyy]
                let input_del = document.createElement('input')
                input_del.type='hidden'
                input_del.name = 'delete'
                input_del.value = keyy
                li.appendChild(input_del)
                let del = document.createElement('button')
                del.type = 'button'
                del.classList.add('fnt_aw-btn')
                del.classList.add('delete-btn')
                del.innerHTML = '<i class="fas fa-trash-alt"></i>'
                li.appendChild(del)
                li.addEventListener('click', function() {
                    post_form('del_user_aff', 'cleaning')
                })
                ul.appendChild(li)
            }
            comment = document.getElementById('t_comment')
            comment.value = data.comment
        }
    })
}

