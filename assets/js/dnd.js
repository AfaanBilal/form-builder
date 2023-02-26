
/*
 *  AMX Form Builder
 *  (c) 2015 Afaan Bilal
 *  
 *  Easily create one-page contact/survey/application forms in seconds!
 *
 */


function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.target.opacity = "0.8";
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    var elIn =  document.getElementById(data);
	var el = document.createElement(elIn.tagName);
	el.className = elIn.className;
    el.className = el.className.replace('dashed-orange-border', '');
    el.innerHTML = document.getElementById(data).innerHTML;
    
    var inLabel;
    if ((inLabel = prompt("Enter a label for the element: ")) === null) {
        flash('Action cancelled by user.', 1500, 'red');
        return;
    }
	
    inLabel = inLabel.replace("'", "\'");
        
    el.querySelector('span').textContent = inLabel;
    
    var elCount = document.getElementById('elCount').getAttribute('value');
    elCount++;
    
    var delBtn = document.createElement('span');
    delBtn.setAttribute('onclick', 'removeElement('+elCount+');');    
    
    el.setAttribute('id', 'div'+elCount);
    
    delBtn.innerText = "X";
    delBtn.style.color = "red";
    delBtn.style.fontSize = "14px";
    delBtn.style.cssFloat = "right";
    delBtn.style.cursor = "pointer";
    
    el.appendChild(delBtn);
    
    var inT = document.getElementById('in');
    
    var inElement;
    
    if ((inElement = el.querySelector('radio')) !== null)
    {
        el.style.padding = "10px";
        inElement.style.padding = "20px";
        
        while (inElement.firstChild !== null)
        {
            inElement.removeChild(inElement.firstChild);
        }
        
        var numOpts = prompt('Enter the number of options for the radio: ');
        
        while (!isFinite(numOpts))
            numOpts = prompt('Please enter a number between 1 and 100\nEnter the number of options for the radio: ');
        
        if (numOpts === null) {
            flash('Action cancelled by user.', 1500, 'red');
            return;
        }
                    
        var optList = '';
        for (i = 1; i <= numOpts; i++)
        {
            var op = prompt('Enter option'+i+': ');
            if (op === null) {
                flash('Action cancelled by user.', 1500, 'red');
                return;
            }
                            
            var optNode = document.createElement('label');
            var spanNode = document.createElement('span');
            var inpNode = document.createElement('input');
            
            optNode.className = "form-checkbox";
            
            spanNode.innerText = op;
            spanNode.innerHTML += "&nbsp;"
            optNode.appendChild(spanNode);
            
            inpNode.setAttribute('type','radio');
            inpNode.setAttribute('name','radioI');
            inpNode.setAttribute('value', op);
            optNode.appendChild(inpNode);
            
            optList += op + ':';
            inElement.appendChild(optNode);
            inElement.innerHTML += "&nbsp;&nbsp;";
        }
        
        inT.textContent += elCount + ',' + 'radio' + ',' + inLabel + ',' + numOpts + ',' + optList + ';'; 
        //alert('radio');
    }
    else if ((inElement = el.querySelector('input')) !== null)
    {
        inElement.setAttribute('placeholder',inLabel);
        //var inValue = '';
        //if ((inValue = prompt('Enter a default value for the element: ')) == null)
        //    return; 
            
        //inElement.setAttribute('value',inValue);
            
        var inType = inElement.getAttribute('type');
        inT.textContent += elCount + ',' + 'input' + ',' + inType + ',' + inLabel + ';';
    }
    else if ((inElement = el.querySelector('select')) !== null)
    {
        while (inElement.firstChild !== null)
        {
            inElement.removeChild(inElement.firstChild);
        }
        
        var numOpts = prompt('Enter the number of options for the dropdown: ');
        
        while (!isFinite(numOpts))
            numOpts = prompt('Please enter a number between 1 and 100\nEnter the number of options for the dropdown: ');
        
        if (numOpts === null) {
            flash('Action cancelled by user.', 1500, 'red');
            return;
        }
                    
        var optList = '';
        for (i = 1; i <= numOpts; i++)
        {
            var op = prompt('Enter option'+i+': ');
            if (op === null) {
                flash('Action cancelled by user.', 1500, 'red');
                return;
            }
            
            var optNode = document.createElement('option');
            optNode.setAttribute('value', op);
            optNode.innerText = op;
            optList += op + ':';
            inElement.appendChild(optNode);
        }
        
        inT.textContent += elCount + ',' + 'select' + ',' + inLabel + ',' + numOpts + ',' + optList + ';'; 
        //alert('select');
    }
    else if ((inElement = el.querySelector('textarea')) !== null)
    {
        inElement.setAttribute('placeholder',inLabel);
        
        inT.textContent += elCount + ',' + 'textarea' + ',' + inLabel + ';';
    }
    
    // append it
    ev.target.appendChild(el);     
    document.getElementById('elCount').setAttribute('value', elCount);
    //alert('Form element added.');
    //alert(inT.textContent);
    flash('Form element added.', 1500, 'green');
    
}

function flash(msg, timeout, color)
{
        var h = document.getElementById('flashMsg');
        h.innerText = msg;
        h.style.color = color;
        
        setTimeout(function(){ 
            h = document.getElementById('flashMsg');
            h.innerText = " "; }, 
        timeout);
}

function submitForm()
{
    var inf = document.getElementById('inf');
    var formTitle = prompt("Enter a title for the form: ");
    var formMsg = prompt("Enter a message to be displayed when someone submits the form successfully: ");
    if (formTitle !== null && formMsg !== null) {
        document.getElementById('it').value = formTitle;
        document.getElementById('im').value = formMsg;
        inf.submit();
    }
}

function removeElement(n)
{
    document.getElementById('div'+n).remove();
    
    var inT = document.getElementById('in');
    
    var ps = inT.textContent.split(';');
    for (var i = 0; i < ps.length; i++)
    {
        if (ps[i].split(',')[0] == n)
            ps[i] = '';
    }
    
    inT.textContent = ps.join(';');
    
    inT.textContent = inT.textContent.replace(/[;]+/g, ';');
    
    if (inT.textContent.split('')[0] == ';') 
        inT.textContent = inT.textContent.substring(1,inT.textContent.split('').length);
    
    if (inT.textContent == ';') inT.textContent = '';
    
    flash('Form element removed.', 1500, 'green');
    
    //alert(inT.textContent);
    
}
