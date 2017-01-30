// JavaScript Document
/**
 * 
 *
 * @author 杨周 <yzhou91@aliyun-inc.com> QQ:89652519
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.yhcms.com
 * @package wind
 */

function Icon_selected(sid,key) {
	var smid = document.getElementById(sid);
	smid.checked = true;
	for(var i=1;i<10;i++){
		var liid = document.getElementById(i+ '_x');
		if(i == key){
			liid.className = 'checked';
		}else{
			liid.className = '';
		}
	}
}
/* 表单验证 */
function  CheckForm(){
	var a = document.getElementsByName("qdxq");
	var b = document.getElementsByName("qdmode");
	var c = document.getElementById('diary_input1');
	var d = document.getElementById('diary_input2');
    var num = 0;
	var nums = 0;
    for (var i=0; i<a.length; i++){
		if(a[i].checked) {
			num++;
		}
	}

	if(u_id <= 0 || u_id == '') {
		alert("对不起, 您还没有登录！");
		return false;
	}else if(num == 0) {
		alert("对不起, 签到心情为必须选择！");
		return false;
	}else if(document.getElementById('qdmode').value=='1' && c.value==''){
		alert("对不起, 今天我想说为必填项！");
		return false;
	}else if(document.getElementById('qdmode').value=='2' && d.value==''){
		alert("对不起, 今天我想说为必选项！");
		return false;
	}else{
		//return true;
		post_ajax(m,mm);//新的表单用ajax提交
	}
}
/* 今天我想说 */
function  CheckDiary(key){
	var a = document.getElementById('qddiary1');
	var b = document.getElementById('qddiary2');
	var c = document.getElementById('qddiary3');
	var ia = document.getElementById('diary_input1');
	var ib = document.getElementById('diary_input2');
	if(key == 1) {
		a.style.display = '';
		b.style.display = 'none';
		c.style.display = 'none';
		ia.name = 'qddiary';
		ib.name = '';
		document.getElementById('qdmode').value='1';
	}else if(key == 2) {
		a.style.display = 'none';
		b.style.display = '';
		c.style.display = 'none';
		ia.name = '';
		ib.name = 'qddiary';
		document.getElementById('qdmode').value='2';
	}else if(key == 3) {
		a.style.display = 'none';
		b.style.display = 'none';
		c.style.display = '';
		ia.name = '';
		ib.name = '';
		document.getElementById('qdmode').value='3';
	}
}
/* 表单提交 */
function post_ajax(m,mm) {
alert('签到成功,你获得'+m+mm+'，感谢你对本站的支持!');
}
/* 内容查看 */
function trid(id) {
	var tr=document.getElementById('tr_'+id);
	var grx=document.getElementById('grx_'+id);
	if(grx.innerHTML=='查看'){
tr.style.display = '';
grx.innerHTML = '关闭';
}else{
tr.style.display = 'none';
grx.innerHTML = '查看';
	}
}
/*查看公告*/
function ckgg() {
	//alert('签到成功,你获得阳光值，感谢你对阳光网的支持!');
var gx=document.getElementById('ckgg_x');
var gy=document.getElementById('ckgg_y');
		if(gy.innerHTML=='查看详情'){
gx.style.display = '';
gy.innerHTML = '关闭查看';
}else{
gx.style.display = 'none';
gy.innerHTML = '查看详情';
	}
}
/* 等级相关 */
function  TableDisplay(id){
	var a = document.getElementById(id);
	if(a.style.display == 'none') {
		a.style.display = '';
	}else{
		a.style.display = 'none';
	}
}
