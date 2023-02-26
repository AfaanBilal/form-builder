<?php

/*
 *  AMX Form Builder
 *  (c) 2015 Afaan Bilal
 *  
 *  Easily create one-page contact/survey/application forms in seconds!
 *
 */



$output = '<form method="post" class="form-validation"><div class="form-title-row"><h1>%title%</h1></div><h2 style="color:red"><?php echo ($e)? \'Please fill in all the fields.\' : \'\'; ?></h2>';
$fNum = 0;

function append_input($inType, $inLabel)
{
    global $output;
    global $fNum;
    
    $output .= '<div class="form-row"><label ';
    $output .= ($inType=='checkbox')?'class="form-checkbox"':'';
    $output .= '><span>'.$inLabel.'</span><input type="'.$inType.'" name="field'.++$fNum.'" placeholder="'.$inLabel.'"></label></div>';
    $output .= '<input type="hidden" name="field'.$fNum.'_label" value="'.$inLabel.'">';
}

function append_select($inLabel, $optList)
{
    global $output;
    global $fNum;
        
    $opts = explode(':', $optList);
    $output .= '<div class="form-row"><label><span>'.$inLabel.'</span><select name="field'.++$fNum.'"><option value="" disabled selected>Choose an option</option>';
    foreach ($opts as $opt){
        if (empty($opt)) continue;
        $output .= '<option>'.$opt.'</option>';
    }   
                            
    $output .=  '</select></label></div>';
    $output .= '<input type="hidden" name="field'.$fNum.'_label" value="'.$inLabel.'">';    
}

function append_textarea($inLabel)
{
    global $output;
    global $fNum;
    
    $output .= '<div class="form-row"><label><span>'.$inLabel.'</span><textarea name="field'.++$fNum.'" placeholder="'.$inLabel.'"></textarea></label></div>';
    $output .= '<input type="hidden" name="field'.$fNum.'_label" value="'.$inLabel.'">';
}

function append_radio($inLabel, $optList)
{
    global $output;
    global $fNum;
    
    ++$fNum;    
    
    $opts = explode(':', $optList);
    $output .= '<div style="padding: 10px" class="form-row"><span style="color: #333;padding-left:30px;">'.$inLabel.'</span><radio style="padding: 20px;">';
    foreach ($opts as $opt){          
        if (empty($opt)) continue;
        $output .= '<label class="form-checkbox"><span>'.$opt.'</span><input type="radio" name="field'.$fNum.'" value="'.$opt.'"></label>&nbsp;&nbsp;';
    }
    $output .= '</radio></div>';
    $output .= '<input type="hidden" name="field'.$fNum.'_label" value="'.$inLabel.'">';    
}

if (isset($_POST['in']))
{
    //purify $_POST
    foreach ($_POST as $k => $v)
    {
        $_POST[$k] = htmlspecialchars($v);    
    }
    
    //print_r($_POST['in']);
    $output = str_replace('%title%', $_POST['title'], $output);
    
    $lines = explode(';', $_POST['in']);
    if (count($lines) < 2) die("Please drag at least one field in the form.");
    foreach($lines as $line)
    {
        $line = trim($line);
        $ps = explode(',',trim($line));
        
        if (count($ps) < 2) continue;
                        
        switch ($ps[1])
        {
            case 'input':
                if (count($ps) < 4) break;
                append_input($ps[2], $ps[3]); 
                break;
            
            case 'select':
                if (count($ps) < 5) break;
                append_select($ps[2], $ps[4]);
                break;
                
            case 'textarea':
                if (count($ps) < 3) break;
                append_textarea($ps[2]);
                break;
                
            case 'radio':
                if (count($ps) < 5) break;
                append_radio($ps[2], $ps[4]);
                break;                
        }
    }
    
    $output .= '<input type="hidden" name="fNum" value="'.$fNum.'"><input type="hidden" name="title" value="'.$_POST['title'].'"><input type="hidden" name="submit" value="1"><div class="form-row"><button type="submit">Submit</button></div>';
            
    
    $infile = file_get_contents("form.php");
    $infile = str_replace('[%title%]', $_POST['title'], $infile);
    $infile = str_replace('[%output%]', $output, $infile);
    $infile = str_replace('[%msg%]', $_POST['msg'], $infile);
    
    $fn = tempnam(sys_get_temp_dir(), uniqid('AMX_') . '.php.txt');   
    file_put_contents($fn, $infile);    
    
    echo "<h2>AMX Form Builder by Afaan Bilal</h2>";
    echo "Copy the following code starting from <b style=\"color:blue;\">'&lt;?php'</b> and paste in a new php file.";
    echo " Upload it to a PHP enabled web server.<br>";
    echo "The received form data will be stored in the same directory in a file named <b style=\"color:blue;\">".$_POST['title'].".csv</b> which can be opened in MS Excel.";
    echo "<hr>";
    highlight_file($fn);
    @unlink($fn);
    
    //echo '<a href="'.$fn.'">Download PHP File</a>';
    exit;
}

?><!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>AMX Form Builder</title>

	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/form-validation.css">
    
    <script src="assets/js/dnd.js"></script>

</head>


	<header>
		<h1>AMX Form Builder</h1>
        <div>
            Designed &amp; Developed by 
            <a href="https://afaan.dev" target="_blank">
                <span>Afaan Bilal</span>
            </a>
        </div>
    </header>

    <div class="main-content">
        <div style="width: 100%;">
                        
            <a style="float: right; margin-right: 50px; font-size: 25px; font-family: Verdana;" class="btn" onclick="submitForm();">Submit Form</a>
            <span id="flashMsg" style="font-size: 28px; font-family: 'Segoe UI';color: green; padding: 5px; margin-left: 50px; background-color: #fff;">&nbsp;</span>
            
        </div><br>
        <div style="width: 50%; float:right;"> 
            <form class="form-validation">    
                <div class="form-title-row">
                    <h1>Drop Here</h1>                                                 
                </div>    
                <div id="dropZone" ondrop="drop(event)" ondragover="allowDrop(event)">
                    
                </div>       
            </form>
        </div>
        
        
        <div style="width: 50%; float: left;">           
            <form class="form-validation"> 
                <div class="form-title-row">
                    <h1>Drag Here</h1>
                 </div>  
            <div id="dragZone" style="width: 100%">
                
                <div id="textInput" class="form-row form-input-name-row dashed-orange-border" draggable="true" ondragstart="drag(event)">
                    <label>
                        <span>Text Input</span>
                        <input type="text" name="name" value="">
                    </label>
                </div>
                
                <div id="emailInput" class="form-row form-input-email-row dashed-orange-border" draggable="true" ondragstart="drag(event)">

                    <label>
                        <span>Email Input</span>
                        <input type="email" name="email" value="">
                    </label>
    
                </div>
                
                <div id="selectInput" class="form-row dashed-orange-border" draggable="true" ondragstart="drag(event)">

                    <label>
                        <span>Dropdown</span>
                        <select name="dropdown">
                            <option>Choose an option</option>
                            <option>Option One</option>
                            <option>Option Two</option>
                            <option>Option Three</option>
                            <option>Option Four</option>
                        </select>
                    </label>
    
                </div>
                
                <div id="checkboxInput" class="form-row dashed-orange-border" draggable="true" ondragstart="drag(event)">

                    <label class="form-checkbox">
                        <span>Checkbox</span>
                        <input type="checkbox" name="checkbox" checked>
                    </label>
    
                </div>
                
                <div style="padding: 10px" id="radioInput" class="form-row dashed-orange-border" draggable="true" ondragstart="drag(event)">
                    
                    <span style="color: #333;padding-left:30px;">Radio Buttons:</span>                        
                    <radio style="padding: 20px;">
                        <label class="form-checkbox">
                            <span>Option1</span>
                            <input type="radio" name="radioI" value="1">
                        </label>
                        &nbsp;&nbsp;
                        <label class="form-checkbox">
                            <span>Option2</span>
                            <input type="radio" name="radioI" value="2" checked>
                        </label>
                        &nbsp;&nbsp;
                        <label class="form-checkbox">
                            <span>Option3</span>
                            <input type="radio" name="radioI" value="3">
                        </label>
                        &nbsp;&nbsp;
                    </radio>
    
                </div>
                
                <div id="textareaInput" class="form-row dashed-orange-border" draggable="true" ondragstart="drag(event)">

                    <label>
                        <span>Textarea</span>
                        <textarea></textarea>
                    </label>
    
                </div>
                
                
            
            </div>
            </form>        
        </div>
        
        <div style="visibility: hidden;">
            <form id="inf" method="post">
                <input type="text" name="title" id="it" />
                <input type="text" name="msg" id="im" />
                <textarea name="in" id="in"></textarea>
            </form> 
        </div>
        <input type="hidden" id="elCount" value="0" /> 

    </div>
    
    <script>
    //document.getElementById('dropZone').contentEditable = true;
    </script>
</body>

</html>
