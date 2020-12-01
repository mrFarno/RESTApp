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
            console.log(data)
            for (let i = 1; i < 5; i++) {
                $('#mt-'+i).prop('checked', false)
                $('#start-mt-'+i).attr('hidden', true)
                $('#end-mt-'+i).attr('hidden', true)
            }
            for (var i in data) {
                check = $('#mt-'+data[i].maf_meal_type)
                console.log(check)
                check.prop('checked', true)
                start = document.getElementById('af_timestart-'+data[i].maf_meal_type)
                $('#start-mt-'+data[i].maf_meal_type).removeAttr('hidden')
                start.hidden = false
                start.value = data[i].maf_timestart.split(' ')[0]
                end = document.getElementById('af_timeend-'+data[i].maf_meal_type)
                $('#end-mt-'+data[i].maf_meal_type).removeAttr('hidden')

                end.hidden = false
                if (data[i].maf_timeend !== null) {
                    end.value = data[i].maf_timeend.split(' ')[0]
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

function post_current() {
    step = document.getElementById('check-step')
    post_form(step.value, 'meals')
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
        dataType : 'json',
        success: function(data) {
            show_toast('success', 'Mise à jour enregistrée')
            if(data.p_id !== undefined) {
                hidden = document.getElementById('p_id')
                hidden.value = data.p_id
                init_products_modal(hidden.value)
            }
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

function submit_pic_form() {
    form = document.getElementById('pic-form')
    form.submit()
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
            input = document.getElementById('upload')
            input.value = eq_id
            contact.innerText = data.eq_fail_contact
            instructions.innerText = data.eq_fail_instructions
        }
    })
}

function upload_eq_pic() {
    upload = document.getElementById('eq_pic')
    input = document.getElementById('upload')
    let formData = new FormData()
    formData.append('upload', input.value)
    formData.append('failed', upload.files[0])
    $.ajax({
        url : 'index.php?page=meals',
        type : 'POST',
        data : formData,
        mimeType: "multipart/form-data",
        processData: false,
        contentType: false,
        dataType : 'json',
        success: function(data) {
            show_toast('success', 'Photo enregistrée')
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
    if (event.target.checked === false) {
        link = document.getElementById('link-failed-'+id)
        link.parentNode.removeChild(link)
    }
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

function init_affectations_modal(id) {
    //
    // select = document.getElementById('t_user_id')
    // ul = document.getElementById('responsibles')
    //
    // ul.innerHTML = ''
    // select.innerHTML = ''
    //
    // selected = document.createElement('option')
    // selected.selected = true
    // selected.disabled = true
    // selected.innerHTML='---Ajouter un responsable---'
    // select.appendChild(selected)
    inpt_date = document.getElementById('date-hidden')
    $.ajax({
        url : 'index.php?page=affectations&date='+inpt_date.value,
        type : 'POST',
        data : 'search='+id,
        dataType : 'html',
        success: function(data) {
            // var li = document.createElement('li')
            // for (var key in data.employees) {
            //     console.log(key, data.employees[key])
            //     let option = document.createElement('option')
            //     option.value = key
            //     option.innerHTML = data.employees[key]
            //     select.appendChild(option)
            // }
            // var input_del = document.createElement('input')
            // input_del.type='hidden'
            // input_del.name = 'delete'
            // input_del.id = 'delete-hidden'
            // li.appendChild(input_del)
            // for (var keyy in data.responsibles) {
            //
            //     li.innerHTML = data.responsibles[keyy]
            //
            //     let del = document.createElement('button')
            //     del.type = 'button'
            //     del.classList.add('fnt_aw-btn')
            //     del.classList.add('delete-btn')
            //     del.innerHTML = '<i class="fas fa-trash-alt"></i>'
            //     li.appendChild(del)
            //     del.addEventListener('click', function() {
            //         let hidden_del = document.getElementById('delete-hidden')
            //         hidden_del.value = keyy
            //         post_form('del_user_aff', 'affectations');
            //         update_affectation_modal();
            //     })
            //     ul.appendChild(li)
            // }
            // comment = document.getElementById('t_comment')
            // comment.value = data.comment
            modal = document.getElementById('modal-content')
            modal.innerHTML = data
            hidden = document.getElementById('t_target_id')
            hidden.value = id
        }
    })
}

function init_temperature_modal(id) {
    inpt_date = document.getElementById('date-hidden')
    $.ajax({
        url : 'index.php?page=production&date='+inpt_date.value,
        type : 'POST',
        data : 'search='+id,
        dataType : 'html',
        success: function(data) {
            modal = document.getElementById('temperature-modal-content')
            modal.innerHTML = data
            hidden = document.getElementById('t_target_id')
            hidden.value = id
        }
    })
}

function del_user_aff(id) {
    var del = document.getElementById('delete-hidden')
    del.value = id
}

function update_affectation_modal() {
    hidden = document.getElementById('t_target_id')
    init_affectations_modal(hidden.value)
}

function init_products_modal(p_id) {
    hidden = document.getElementById('p_id')
    hidden.value = p_id
    ctnr = document.getElementById('btn-ctnr')
    ctnr.innerHTML = ''

    $.ajax({
        url : 'index.php?page=products',
        type : 'POST',
        data : 'search='+p_id,
        dataType : 'json',
        success: function(data) {
            input = document.getElementById('product-current-input')
            label = document.getElementById('label')
            if (Array.isArray(data)) {
                input.type = data[0]
                input.name = data[2]
                if(data[0] == 'checkbox' || data[0] == 'file') {
                    btn = document.createElement('button');
                    if (data[0] == 'checkbox') {
                        btn.type = 'button'
                        btn.addEventListener('click', function() {
                            post_form('products', 'products')
                            init_products_modal(p_id)
                        })
                    } else {
                        btn.type = 'submit'
                        form = document.getElementById('step-form')
                        form.onsubmit = ''
                    }
                    btn.innerHTML = 'Ok'
                    btn.classList.add('btn')
                    btn.classList.add('btn-outline-success')
                    btn.classList.add('width100')

                    ctnr.appendChild(btn)
                }
                label.innerHTML = data[1]+' : '
            } else {
                input.style.display = 'none'
                label.innerHTML = data
            }
        }
    })
}

function employee_tmp_modal(rs_id) {
    hidden = document.getElementById('rs_id')
    hidden.value = rs_id
    $.ajax({
        url : 'index.php?page=production',
        type : 'POST',
        data : 'search='+rs_id,
        dataType : 'json',
        success: function(data) {
            input = document.getElementById('recipe-current-input')
            label = document.getElementById('label')
            if (Array.isArray(data)) {
                input.type = data[0]
                input.name = data[2]

                label.innerHTML = data[1]+' : '
            } else {
                input.style.display = 'none'
                label.innerHTML = data
            }
        }
    })
}

$('#products_modal').on('hide.bs.modal', function () {
    window.location.reload(true)
});

function product_form(event) {
    event.preventDefault()
    post_form('products', 'products')
    hidden = document.getElementById('p_id')
    console.log(hidden.value)
    init_products_modal(hidden.value)
}

function recipe_form(event) {
    event.preventDefault()
    post_form('recipe', 'production')
    hidden = document.getElementById('rs_id')
    console.log(hidden.value)
    employee_tmp_modal(hidden.value)
}

function update_task_status(id) {
    $.ajax({
        url : 'index.php?page=cleaning',
        type : 'POST',
        data : 'status='+id,
        success: function() {
            show_toast('success', 'Mise à jour réussie')
        }
    })
}

