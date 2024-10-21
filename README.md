## 題目一
### 請寫出一條查詢語句 (SQL)，列出在 2023 年 5 月下訂的訂單，使用台幣付款且5月總金額最 多的前 10 筆的旅宿 ID (bnb_id), 旅宿名稱 (bnb_name), 5 月總金額 (may_amount) 
select o.bnb_id as bnb_id, b.name as bnb_name,sum(amount) as may_amount from orders o
left join bnbs b on o.bnb_id = b.id
where o.created_at >= '2023-05-01' and o.created_at < '2023-06-01' and o.currency='TWD'
group by o.bnb_id, b.name 
order by may_amount desc
limit 10;

## 題目二 
### 在題目一的執行下，我們發現 SQL 執行速度很慢，您會怎麼去優化？請闡述您怎麼判斷與優化的方式
可以建立索引，可將較常用來join table的欄位建立索引，例如bnb_id。
或是將容易用來作為查詢條件的欄位建立索引，例如currency、created_at。

## SOLID 與 設計模式
### 單一職責：
OrderRequest只用來驗證參數與回傳驗證結果。
Service單純撰寫特定單一邏輯。Ex.USD轉換成TWD的結果。
Controller用來接收參數並回傳結果。
### 開放/封閉原則
UpperCase為新增加的自定義驗證邏輯，表示驗證首字母為大寫的英文字句，若其餘API也需要這樣的規則可直接使用，而不必改動原始的程式碼。

## 設計模式
策略模式，定義一系列的邏輯，讓他們可獨立被調用，根據不同情境進行驗證與運算。
服務模式，將貨幣轉換的邏輯提取到一個專門的服務類別中。