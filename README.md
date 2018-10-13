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

### 注意事项
`curl_request` 方法里有两行 curl_setopt，在 Use ShadowsocksR 注释下方，如果你不使用 SSR 或其他 Socks5 代理做下载加速，请注释或删除这两行。
