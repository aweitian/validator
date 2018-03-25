# 常用验证 组件
## 安装组件
使用 composer 命令进行安装或下载源代码使用。
> composer require aweitian/validator
>

### 格式
> CMD[:参数列表] | CMD[:参数列表]

### 可用的规则CMD
- bail (同一条记录第一个规则验证失败了是否继续,默认为否)
- empty
- strict
- bool
- '[eq|ne|gt|ge|lt|le]:pwd2'
- email
- url
- date
- datetime
- time
- year
- required:taw   required
- str:20   str:3,9   str:,2
- range:aaa,bbb,ccc,dddd
- int:3   int:,9   int:4,9
- number:3    number,9.02,number:5.4,999.99   number:2,3,true,true    min,max,unsigned,intonly
- regexp:#^\d+$#
- fun:calss::handle
