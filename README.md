
webz.io client for PHP
============================

A simple way to access the [Webz.io](https://webz.io) API from your PHP code:

```php
// API Key from: https://webz.io/dashboard
Webz::config("API_KEY");

//Perform a "filterWebContent" query using "United States" as our keywords.
$params = array("q"=>"United States", "size"=>"3");
$result = Webz::query("filterWebContent", $params);

//Fetch the next results using the same terms.
$result = Webz::get_next();
```
API Key
-------

To make use of the webz.io API, you'll need an API key.

To get one, sign up at:
https://webz.io/auth/signup

And your key will be available here:
https://webz.io/dashboard


Usage
-----------

To get started, you'll need to import the class and configure it with your API key:

```php
require_once('webz.php');

// API Key from: https://webz.io/dashboard
Webz::config("API_KEY");
```

<br />

**API Endpoints**

The first parameter the query() function accepts is the API endpoint string. Available endpoints:
* filterWebContent - access to the news/blogs/forums/reviews API
* productFilter - access to data about eCommerce products/services
* darkFilter - access to the dark web (coming soon)

Now you can make a request and inspect the results:

```php
//Helper method to print result:
function print_filterwebdata_titles($api_response)
{
    if($api_response == null)
    {
        echo "<p>Response is null, no action taken.</p>";
        return;
    }
    if(isset($api_response->posts))
        foreach($api_response->posts as $post)
        {
            echo "<p>" . $post->title . "</p>";
        }
}

//Perform a "filterWebContent" query using "United States" as our keywords.
$params = array("q"=>"United States", "size"=>"3");
$result = Webz::query("filterWebContent", $params);
print_filterwebdata_titles($result);
```

You can traverse the structure as you would any PHP array:

```php
//Print more detailed information about the article:

$params = array("q"=>"United States", "size"=>"1");
$result = Webz::query("filterWebContent", $params);

foreach($result->posts as $post)
{
    echo "<p>Site: <b>" . $post->thread->site . "</b></p>";
    echo "<p>Categories:</p>";
    echo "<ul>";
    foreach($post->thread->site_categories as $category) {
        echo "<li>" . $category . "</li>";
    }
    echo "</ul>";
}
```

<br />

Depending on the endpoint used, the resulting JSON array could provide "posts", "products", ...
You can view the JSON in the browser to get a clearer picture of the return data.
In order to view the data in the browser, we can enable a debug flag to expose the URL fed to cURL:

```php
//If true, echoes the parameterised Webz API URL before executing request.
Webz::enable_debug(true);
```


Full documentation
------------------

* ``Webz::config(api_key)``

  * api_key - your API key

* ``Webz::query(end_point_str, params)``

  * end_point_str
    * filterWebContent - access to the news/blogs/forums/reviews API
    * productFilter - access to data about eCommerce products/services
    * darkFilter - access to the dark web (coming soon)
  * params: A key value dictionary. [Read about the available parameters](https://docs.webz.io/).

* ``Webz::get_next()`` - Fetches the next page of results using the same parameters.

* ``Webz::enable_debug(debug_enabled)``

  * debug_enabled - boolean, If true, echoes the parameterised Webz API URL before executing requests.


Polling
------------------

It is possible to continue a search to fetch more results using the same parameters:

```php
//Perform a "productFilter" query using "United Kingdom" as our keywords.
$params = array("q"=>"United Kingdom", "size"=>"1");
$result = Webz::query("productFilter", $params);
print_productsearch_titles($result);

//Fetch the next results using the same terms.
$result = Webz::get_next();
print_productsearch_titles($result);

$result = Webz::get_next();
print_productsearch_titles($result);

//...
//When $result is null, there are no more results available.
```

License
------------------
The code of this repository is published under the MIT license
