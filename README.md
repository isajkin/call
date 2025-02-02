Набор функций для обработки текста с помощью инъекции в тело текста произвольных выражений вместо использования eval().
Используется php-функция call_user_func_array(). Для использования массивов в вычислениях вместо array() применяются функции:
call_array(a,b,c) и call_aray_para(key1,val1,key2,val2). При построении выражений можно использовать 18 операндов.
Область применения: Настроечные файлы конфигураций в формате txt,json,xml.
Пример 1:
$x="call:.text1,text2,text3";
echo call_exec($x);
output:
text1text2ttext3
Пример 2:
$x="abc(call:+,4,5,6)def";
echo call_exec($x);
output:
abc15def
Пример 3:
$x="call:strtoupper,window";
echo call_exec($x);
output:
WINDOW
