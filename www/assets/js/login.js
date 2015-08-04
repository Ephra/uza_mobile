/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function login(){
    var url="http://inetstz.com/index.html";
    $.get(url,{pg:'login',method:'login'},function(data){
        console.log(data);
    });
}

