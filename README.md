# Login2See Advanced

**Flarum Extension by Greatis**

Adds `[login]...[/login]` BBCode that hides content from guests and unconfirmed users, making it visible only to logged-in, email-verified members.

---

## 🔧 Features

- Protect parts of posts using simple BBCode:
  ```bbcode
  [login]This content is visible only to logged-in, verified users.[/login]
Guests and unverified users will see a message prompting them to log in or confirm their email.

Supports Markdown and other BBCodes inside [login]...[/login].

Works seamlessly with Flarum core and most third-party extensions.

🧩 Installation
In your Flarum root directory, run:

composer require greatis/login2see-advanced:*

⚙️ Updating
composer update greatis/login2see-advanced
php flarum cache:clear

🧠 Usage
Wrap the protected content with the [login] tag:

[login]
This section is visible only to logged-in, verified users.
[/login]
Guests and users who have not confirmed their email will see a message like:

Please log in or verify your account to view this content.

🧾 License
Licensed under the MIT License.

💬 Credits
Developed by Greatis Software
Login2See Advanced — make your private content truly members-only.






