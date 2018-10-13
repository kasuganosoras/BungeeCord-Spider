# BungeeCord-Spider
BungeeCord Jenkins 自动爬取更新

### 依赖扩展
| 扩展名称 | 扩展作用 |
| ------- | ------- |
| xml | 读取 Jenkins RSS |
| curl | 发起 Http 请求 |

### 使用方法
第 `95` 行的 `$root` 改为你的储存路径。

例如 
````php
$root = "./";
````

然后运行

````bash
php BungeeCord.php
````

接下来挂着就行了，每天更新一次。
