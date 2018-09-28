# 常用验证 组件
## 安装组件
使用 composer 命令进行安装或下载源代码使用。
> composer require aweitian/validator
>

### 格式
> CMD[:参数列表] | CMD[:参数列表]

### 可用的CMD
- bail (同一条记录第一个规则验证失败了是否继续,默认为否)
- empty
- strict
- bool
- array
- separator 字符分隔数组批量验证 (通用)
- '[eq|ne|gt|ge|lt|le]:pwd2'
- email
- url
- date
- datetime
- time
- year
- required:taw   required
- str:length   str:min,max   str:,max   str:min, （保留两边空白）
- string:min   string:length:length string:min,max string:min, string:,max （不保留两边空白）
- range:aaa,bbb,ccc,dddd
- int:3   int:,9   int:4,9
- number:3    number,9.02,number:5.4,999.99   number:2,3,true,true    min,max,unsigned,intonly
- regexp:#^\d+$#
- fun:calss::handle

### 例子
- separator:{or}|int 可以验证 1|234|4523|24334
- separator:{colon}|string:2 可以验证 ab:234:aec:adore
- separator:#|string:3,3 可以验证 abc#123#www#are