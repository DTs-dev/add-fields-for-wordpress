# WordPress theme function for field creating in metabox

### Inclusion
For this add-on to work, add the contents of the repository to your theme folder and include the “custom-fields.php” file in the “function.php” file.

### Usage
To add a new metabox with new custom field, initialize the "add_field" function in anonymous function in file "custom-fields.php", as follows.
```
add_field( $cfield_name, $cfield_title, $post_type, $context, $input_type, $template );
```

where:

- `$cfield_name` - name of custom field.
- `$cfield_title` - title of metabox with custom field.
- `$post_type` - metabox output for a single post type or for several post types in an array (default: NULL - any).
- `$context` - metabox display area: normal, side, advanced, after_title or custom context (default: 'side').
- `$input_type` - type of input field in html form: input, textarea or multi_input (default: 'input').
- `$template` - metabox output for a specific post template (default: FALSE).

**The file contains 3 activated examples.**
