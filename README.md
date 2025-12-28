# Trongate v2 Weekly Schedules Module

A complete **weekly_schedules** module for **Trongate v2** that demonstrates a full-featured **CRUD** (Create, Read, Update, Delete) application for tracking employee work schedules by week.

This repository provides a ready-to-use example of building a weekly scheduling system using the Trongate PHP framework (version 2). It includes pagination, form validation, secure admin access, **native HTML5 week input handling**, and clean separation of concerns.

## Features

- ✅ Paginated schedule listing with selectable records per page (10, 20, 50, 100)
- ✅ Create new weekly schedule records with week/year selection
- ✅ View detailed schedule information with formatted week display
- ✅ Update existing schedule records (with form repopulation on validation errors)
- ✅ Safe delete with confirmation page
- ✅ **Native HTML5 week picker** for week/year selection (zero JavaScript required)
- ✅ **Simple week handling** using ISO 8601 YYYY-W## format
- ✅ **No conversion needed** - same format for form, database, and display
- ✅ **VARCHAR storage** for week values (human-readable in database)
- ✅ Beautiful week formatting for display (e.g., "Week 52, 2025")
- ✅ Date range display for weeks (e.g., "Dec 22 - Dec 28, 2025")
- ✅ Form validation including week validation
- ✅ CSRF protection on all forms
- ✅ Admin security checks on all actions
- ✅ Responsive back navigation and flash messages
- ✅ Clean, well-documented code following Trongate v2 best practices

## Database Table

The `weekly_schedules.sql` file creates a `weekly_schedules` table with the following columns:
- `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `employee_name` (VARCHAR 50)
- `work_week` (VARCHAR 8) - stores week in YYYY-W## format (e.g., 2025-W52)
- `tasks` (TEXT)

## Prerequisites

- Trongate v2 framework (latest version recommended)
- PHP 8.0+
- MySQL/MariaDB database
- Web server with URL rewriting enabled

Visit the official site: [trongate.io](https://trongate.io)

## Installation

1. **Install Trongate v2** (if not already done):
   - Download or clone the official framework from GitHub: [https://github.com/trongate/trongate-framework](https://github.com/trongate/trongate-framework)
   - For full documentation and guides, visit: [trongate.io/documentation](https://trongate.io/documentation)

2. **Add the module**:
   - Copy the `weekly_schedules` folder into your project's `modules` directory:
     ```
     modules/
       weekly_schedules/
         Weekly_schedules.php
         Weekly_schedules_model.php
         views/
           create.php
           manage.php
           show.php
           delete_conf.php
           not_found.php
     ```

3. **Create the database table**:
   - Import `weekly_schedules.sql` into your database (e.g., via phpMyAdmin or command line).

4. **Access the module**:
   - Log in to your Trongate admin panel.
   - Visit: `https://your-domain.com/weekly_schedules` or `https://your-domain.com/weekly_schedules/manage`

## URL Routes

- List schedules: `/weekly_schedules` or `/weekly_schedules/manage` (with pagination: `/weekly_schedules/manage/{page}`)
- Create schedule: `/weekly_schedules/create`
- View schedule: `/weekly_schedules/show/{id}`
- Edit schedule: `/weekly_schedules/create/{id}`
- Delete confirmation: `/weekly_schedules/delete_conf/{id}`
- Set records per page: `/weekly_schedules/set_per_page/{option_index}`

## Module Structure

```
weekly_schedules/
├── Weekly_schedules.php        # Main controller with CRUD operations
├── Weekly_schedules_model.php  # Data layer with week formatting methods
└── views/
    ├── create.php              # Create/Edit form
    ├── manage.php              # Paginated list view
    ├── show.php                # Detail view
    ├── delete_conf.php         # Delete confirmation
    └── not_found.php           # 404 error page
```

## Key Features Explained

### Native HTML5 Week Input

This module uses the **native HTML5 week picker** via Trongate's `form_week()` helper:

```php
echo form_week('work_week', $work_week);
```

**Benefits:**
- ✅ Zero JavaScript required
- ✅ Works on all modern browsers (Chrome, Firefox, Safari, Edge)
- ✅ Native mobile keyboards and pickers
- ✅ Accessible by default
- ✅ Always submits in ISO 8601 format (YYYY-W##)
- ✅ Browser displays in user's locale format automatically

### Simple Week Handling

Like month inputs, week inputs require **no conversion**:

**Form Input:** `2025-W52`
↓ (no conversion needed!)
**Database Storage:** `2025-W52`
↓ (simple formatting)
**Display:** "Week 52, 2025" or "Dec 22 - Dec 28, 2025"

### Week Storage

Weeks are stored in a `VARCHAR(8)` column in YYYY-W## format:
- Human-readable in database
- Easy to query by year or week
- No conversion between form and database
- Works with standard SQL string functions

### Week Display Formatting

The model includes a `prepare_for_display()` method that formats weeks for human-readable display:

```php
// Database: 2025-W52
// Full display: Week 52, 2025
// Short: W52 2025
// Date range: Dec 22 - Dec 28, 2025
```

### Validation Rules

The module demonstrates proper validation including:
- Required fields
- String length limits
- **Week format validation** using `valid_week` rule

```php
$this->validation->set_rules('work_week', 'work week', 'required|valid_week');
```

## Development Patterns Demonstrated

### 1. The Three-Method Form Pattern
- `create()` - Display form
- `submit()` - Process submission
- `show()` - Display success/result

### 2. Create/Update Pattern (No Conversion Needed!)
- Single form for both creating and editing
- Week value works in both directions without conversion
- Database value = Form value = YYYY-W## format
- Proper segment type-casting: `segment(3, 'int')`

### 3. POST-Redirect-GET Pattern
- Prevents duplicate submissions on refresh
- Uses `set_flashdata()` for success messages
- Clean URL after form submission

### 4. Data Formatting (Model Methods)
- `prepare_for_display()` - Formats week for human-readable output
- Multiple format options (full, short, date range)
- Clear separation between storage format and display format

### 5. Pagination Implementation
- Session-based per-page selection
- Proper offset calculation
- Clean pagination helper integration

## Code Examples

### The Week Input Field
```php
echo form_week('work_week', $work_week);
// Renders: <input type="week" name="work_week">
// Always submits in YYYY-W## format
```

### Week Validation
```php
$this->validation->set_rules('work_week', 'work week', 'required|valid_week');
// Validates ISO 8601 YYYY-W## format
```

### Storing Week Data (No Conversion!)
```php
// Get from form (YYYY-W## format)
$work_week = post('work_week', true); // "2025-W52"

// Store directly - no conversion needed!
$data['work_week'] = $work_week;

// Save to database
$this->db->insert($data, 'weekly_schedules');
```

### Loading Week Data for Editing (No Conversion!)
```php
// Get from database (already in YYYY-W## format)
$record = $this->db->get_where($update_id, 'weekly_schedules');
// $record->work_week = "2025-W52"

// Use directly in form - no conversion needed!
$data['work_week'] = $record->work_week;

// Pass to view
$this->view('schedule_form', $data);
```

### Week Display Formatting
```php
// Model method formats for display
$parts = explode('-W', $data['work_week']); // ["2025", "52"]
$year = $parts[0];
$week = ltrim($parts[1], '0'); // Remove leading zero: "52"
$data['work_week_formatted'] = 'Week ' . $week . ', ' . $year;
// Result: "Week 52, 2025"
```

### Getting Week Date Range
```php
// Calculate date range for the week
$date = new DateTime();
$date->setISODate((int)$year, (int)$week_num);
$week_start = $date->format('M j'); // "Dec 22"

$date->modify('+6 days');
$week_end = $date->format('M j, Y'); // "Dec 28, 2025"

$date_range = $week_start . ' - ' . $week_end;
// Result: "Dec 22 - Dec 28, 2025"
```

## Important Week Concepts

### ISO 8601 YYYY-W## Format
- This is what HTML5 week inputs use
- Example: `2025-W52`
- Zero-padded weeks: `W01` through `W52` (or `W53`)
- Weeks start on Monday
- Week 1 contains the first Thursday of the year

### ISO 8601 Week Numbering Rules
- Weeks start on Monday (not Sunday!)
- Week 1 is the first week with a Thursday
- This means January 1st might be in Week 52 or 53 of the previous year
- Most years have 52 weeks, some have 53

### Database Storage
- Use `VARCHAR(8)` column type
- Stores exactly as submitted: `2025-W52`
- Human-readable in database queries
- Easy to filter by year or specific weeks

### No Conversion Needed!
Like month inputs, week inputs are beautifully simple:
```php
// Form submits: "2025-W52"
// Store in DB: "2025-W52" (no conversion!)
// Load from DB: "2025-W52" (no conversion!)
// Display: "Week 52, 2025" (just format!)
```

## Customization

### Changing Week Display Format

Edit the `prepare_for_display()` method in `Weekly_schedules_model.php`:

```php
// Current format: "Week 52, 2025"
$data['work_week_formatted'] = 'Week ' . $week . ', ' . $year;

// Short format: "W52/2025"
$data['work_week_formatted'] = 'W' . $week . '/' . $year;

// Verbose: "Week 52 of 2025"
$data['work_week_formatted'] = 'Week ' . $week . ' of ' . $year;
```

### Setting Current Week as Default

Add this to your controller's `create()` method:

```php
if ($data['work_week'] === '') {
    $year = date('Y');
    $week = str_pad(date('W'), 2, '0', STR_PAD_LEFT);
    $data['work_week'] = $year . '-W' . $week;
}
```

### Adding Week Range Constraints

Restrict week selection to specific range:

```php
// Controller
$current_year = date('Y');
$current_week = str_pad(date('W'), 2, '0', STR_PAD_LEFT);

$data['min_week'] = ($current_year - 1) . '-W01'; // Start of last year
$data['max_week'] = $current_year . '-W' . $current_week; // Current week

// View
$attrs = ['min' => $min_week, 'max' => $max_week];
echo form_week('work_week', $work_week, $attrs);
```

### Making Work Week Optional

Change the validation rule in `Weekly_schedules.php`:

```php
// From:
$this->validation->set_rules('work_week', 'work week', 'required|valid_week');

// To:
$this->validation->set_rules('work_week', 'work week', 'valid_week');
```

## Querying by Week in Database

The VARCHAR storage makes querying straightforward:

```php
// Get all schedules for a specific week
$sql = "SELECT * FROM weekly_schedules WHERE work_week = '2025-W52'";

// Get all schedules for a specific year
$sql = "SELECT * FROM weekly_schedules WHERE work_week LIKE '2025-%'";

// Get schedules between two weeks
$sql = "SELECT * FROM weekly_schedules 
        WHERE work_week >= '2025-W01' 
        AND work_week <= '2025-W52'";

// Order by week (descending - most recent first)
$sql = "SELECT * FROM weekly_schedules ORDER BY work_week DESC";

// Get current week's schedules
$current_week = date('Y') . '-W' . str_pad(date('W'), 2, '0', STR_PAD_LEFT);
$sql = "SELECT * FROM weekly_schedules WHERE work_week = :week";
```

## Troubleshooting

**Module not showing?**
- Ensure the `weekly_schedules` folder is in `modules/` directory
- Verify Weekly_schedules.php and Weekly_schedules_model.php are in weekly_schedules/ root
- Check folder permissions (755 for directories, 644 for files)
- Verify you're logged into the admin panel

**Week picker not appearing?**
- HTML5 week inputs work in all modern browsers
- Very old browsers fall back to text input
- Users can type YYYY-W## format manually

**Validation errors?**
- Check that all required fields are filled
- Work week must be in YYYY-W## format
- Ensure week is zero-padded (W01-W52, not W1-W52)

**Database errors?**
- Verify work_week column is VARCHAR(8)
- Confirm no conversion code is interfering
- Check that weeks are properly zero-padded

## Browser Compatibility

The native HTML5 week input is supported by:
- ✅ Chrome (all versions)
- ✅ Firefox (all versions)
- ✅ Safari 14.1+
- ✅ Edge (all versions)
- ✅ Mobile browsers (iOS Safari, Android Chrome)

**Note:** Very old browsers (IE 11 and earlier) will render week inputs as text fields. Users can still type weeks manually in YYYY-W## format, and validation will ensure correctness.

## Security Features

- ✅ CSRF token validation on all forms
- ✅ Admin authentication checks on all methods
- ✅ SQL injection prevention via prepared statements
- ✅ XSS prevention via `out()` function in views
- ✅ Week format validation
- ✅ Delete confirmation to prevent accidental deletion

## Why VARCHAR for Week Storage?

**VARCHAR(8) Advantages:**
- ✅ Stores exactly what the form submits (YYYY-W##)
- ✅ No conversion needed in either direction
- ✅ Human-readable in database queries
- ✅ Simple to filter by year or week
- ✅ Direct string comparison works perfectly
- ✅ Follows ISO 8601 standard format

## Contributing

Issues, suggestions, and pull requests are welcome! Feel free to fork and improve this example module.

## License

Released under the same open-source license as the Trongate framework (MIT-style - permissive and free to use).

## Learn More

- [Trongate Framework](https://trongate.io)
- [Trongate Documentation](https://trongate.io/documentation)

Happy weekly scheduling with Trongate! 📅📋
