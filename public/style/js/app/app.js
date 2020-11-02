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
    var modal = event.target.getAttribute("data-target")
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

