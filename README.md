# Appliance Fit Check — WordPress Plugin

A lightweight, mobile-friendly appliance fit calculator for WordPress.
No backend, no database — pure front-end.

---

## Installation

1. Upload the entire `appliance-fit-check` folder to:
   `/wp-content/plugins/appliance-fit-check/`

   Your plugin directory should look like this:
   ```
   /wp-content/plugins/appliance-fit-check/
   ├── appliance-fit-check.php
   └── assets/
       ├── appliance-fit-check.css
       └── appliance-fit-check.js
   ```

2. Log in to your WordPress Admin.

3. Go to **Plugins → Installed Plugins**.

4. Find **Appliance Fit Check** and click **Activate**.

---

## Usage

### Option A — Elementor (recommended)

1. Open the page in **Elementor**.
2. Drag a **Shortcode** widget onto the page.
3. Paste the shortcode into the widget:
   ```
   [appliance_fit_check]
   ```
4. Click **Update / Publish**.

### Option B — Block Editor (Gutenberg)

1. Add a **Shortcode** block.
2. Enter:
   ```
   [appliance_fit_check]
   ```
3. Update the page.

### Option C — Classic Editor

1. In the body of the page/post, type:
   ```
   [appliance_fit_check]
   ```
2. Update the page.

---

## Shortcode Attributes (Optional)

You can customise the app download links:

```
[appliance_fit_check
    app_store_url="https://apps.apple.com/your-app-link"
    play_store_url="https://play.google.com/store/apps/your-app-link"
]
```

| Attribute        | Default                        | Description                    |
|------------------|--------------------------------|--------------------------------|
| `app_store_url`  | `https://apps.apple.com`       | Link to your iOS app           |
| `play_store_url` | `https://play.google.com`      | Link to your Android app       |

---

## Fit Logic

```
clearance = opening_dimension − product_dimension
(rounded to 2 decimal places before comparison)
```

| Clearance Range       | Result                  |
|-----------------------|-------------------------|
| ≥ 0.25 in             | ✓  Fits Perfectly       |
| 0.00 to 0.24 in       | ⚠  Tight Fit            |
| −0.01 to −0.25 in     | ⚙  Requires Modification|
| ≤ −0.26 in            | ✕  Does Not Fit         |

The **worst result** across Height, Width, and Depth is shown as the final verdict.

---

## Supported Appliance Types

- Refrigerator
- Dishwasher
- Wall Oven

*(More can be added later — see Customisation below.)*

---

## Customisation

### Adding more appliance types

Open `appliance-fit-check.php` and find the `<select>` block inside `render_shortcode()`.
Add new `<option>` entries following the existing pattern:

```html
<option value="range">Range / Stove</option>
<option value="microwave">Microwave</option>
```

### Changing the measuring guide link

Search for `https://appliancefitcheck.org` inside `appliance-fit-check.php`
and replace it with your target URL.

### Styling

All visual styles are in `assets/appliance-fit-check.css`.
CSS custom properties (variables) at the top of the file control
colours, border radius, and shadows — tweak those first.

---

## Performance Notes

- CSS and JS are enqueued on **every front-end page** (necessary because
  Elementor widgets can be placed anywhere and page content is assembled late).
- Combined file size is under 12 KB (uncompressed). Gzip brings this
  well under 4 KB.
- No jQuery dependency — pure vanilla JavaScript.
- No external network requests.

---

## Compatibility

- WordPress 5.5+
- Elementor (free or Pro) ✓
- Gutenberg block editor ✓
- Classic Editor ✓
- All modern browsers + iOS Safari / Android Chrome ✓

---

## Changelog

### 1.0.0
- Initial release
- Appliance types: Refrigerator, Dishwasher, Wall Oven
- Full fit logic with clearance breakdown
- App Store & Google Play CTA
- Mobile-responsive layout
