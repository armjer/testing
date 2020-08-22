function Validator() {

	/*
	 * validations
	 *
	 * Define validations per field. 
	 *
	 */
	var validations  = {
        'title': ['NOT_EMPTY'],
		'firstname': ['NOT_EMPTY', 'LETTERS_ONLY'],
		'lastname': ['NOT_EMPTY', 'LETTERS_ONLY'],
		'email': ['NOT_EMPTY', 'EMAIL'],
		'password': ['NOT_EMPTY', 'PASSWORD'],
		'confirmpassword': ['NOT_EMPTY', 'CONFIRM_PASSWORD'],
		'birthday_month': ['SELECTED'],
		'birthday_day': ['SELECTED'],
		'birthday_year': ['SELECTED']
	}
	
	var errorExist = false;
	
	var notEmpty = function(value) {
		var errorText = LOCALE['validation_required'];

		return (value.length?'':errorText);
	}
	
	var lettersOnly = function(value) {
		var errorText = LOCALE['validation_letters'];

		return (/^[a-zA-Z]*$/.test(value)?'':errorText);
	}
	
	var email = function(value) {
		var errorText = LOCALE['validation_email'];

		return (/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/.test(value)?'':errorText);
	}
	
	var password = function(value) {
		var errorText = LOCALE['validation_password'];

		return (/^(?=.*\d)(?=.*[A-Z])/.test(value)?'':errorText);
	}
	
	var confirmPassword = function(value, confirmValue) {
		var errorText = LOCALE['validation_password_confirm'];

		if(value && value.length) {
			return (value == confirmValue? '':errorText);
		}
		
		return errorText;
	}
	
	var selected = function(value) {
		var errorText = LOCALE['validation_birthdate'];
		
		return (value == '0'?errorText:'');
	}
		
	var showErrors = function(errors) {
		var label;
		for(name in errors) {
			try {
				if(name.indexOf('birthday')<0) {
					label = document.getElementById('err_' + name);
				}
				else {
					label = document.getElementById('err_birthday');
				}
				label.innerHTML = errors[name];
			} catch (e) {

			}
		}
	}
	var validate = function(data, name, errors) {
		var validation; 
		validation = validations[name];
			if(validation) {
				for(key in validation){
					if(!errors[name]) {
						switch(validation[key]) {
							case 'NOT_EMPTY':
								error = notEmpty(data[name]);
								break;
							case 'LETTERS_ONLY':
								error = lettersOnly(data[name]);
								break;	
							case 'EMAIL':
								error = email(data[name]);
								break;
							case 'PASSWORD':
								error = password(data[name]);
								break;
							case 'CONFIRM_PASSWORD':
								error = confirmPassword(data['password'], data[name]);
								break;	
							case 'SELECTED':
								error = selected(data[name]);
								break;
						}
						errors[name] = error;
						if(error) {
							errorExist = true;
						}
					}
				}
			}
		return errors;	
	}
	
	this.validateForm = function(form) {
		var data = {};
		var inputs = form.getElementsByTagName('input');
		var selectBoxes = form.getElementsByTagName('select');
		
		for(key in inputs) {
			data[inputs[key].name] = inputs[key].value;
		}
		for(key in selectBoxes) {
			data[selectBoxes[key].name] = selectBoxes[key].value;
		}
		
		errors = {};
		for(name in data) {
			errors = validate(data, name, errors);
		}
		
		showErrors(errors);
		
		if(errorExist) {
			return false;
		}
		
	}

	this.validateField = function(obj, passObj) {
		errorExist = false;
		var errors = {};
		var data = {};
		name = obj.name;
		data[name] = obj.value;
		if(passObj) {
			data['password'] = passObj.value;
		}
		errors = validate(data, name, errors);
		showErrors(errors);
	}
	
	this.validateBirthday = function(month, day, year) {
		var errors = {};
		errors[month.name] = selected(month.value);
		if(!errors[month.name]) {
			errors[day.name] = selected(day.value);
			if(!errors[day.name]) {
				if(!errors[year.name]) {
					errors[year.name] = selected(year.value);
				}
			}
		}
		showErrors(errors);
	}
}