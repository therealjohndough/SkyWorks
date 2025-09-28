# Development Guide - SkyWorks

## Getting Started

### Prerequisites
- Node.js 16 or higher
- npm or yarn
- WordPress 6.0+ (for WordPress theme)
- PHP 8.0+ (for WordPress theme)

### Quick Setup
```bash
git clone https://github.com/therealjohndough/SkyWorks.git
cd SkyWorks
npm install
npm run build
```

## Project Structure

```
SkyWorks/
├── wp-theme/                 # WordPress theme
│   ├── assets/
│   │   ├── js/              # JavaScript source
│   │   └── scss/            # SCSS source
│   ├── inc/                 # PHP includes
│   ├── template-parts/      # Reusable template parts
│   └── *.php               # Theme templates
├── dist/                    # Built assets
├── headless/               # Headless implementations
├── brand-assets/           # Brand resources
└── docs/                  # Documentation
```

## Development Workflow

### 1. Local Development
```bash
# Start development with file watching
npm run dev

# This will:
# - Watch for SCSS changes
# - Rebuild JavaScript bundles
# - Auto-reload browser (if configured)
```

### 2. Building for Production
```bash
npm run build

# Outputs:
# - dist/main.css (compressed)
# - dist/main.bundle.js (minified)
# - dist/animations.bundle.js (minified)
```

### 3. Linting
```bash
# Lint SCSS
npm run lint:css

# Lint JavaScript
npm run lint:js
```

## WordPress Theme Development

### Theme Structure
- `functions.php` - Main theme setup
- `header.php` / `footer.php` - Layout templates
- `index.php` - Main template
- `single.php` - Single post template
- `inc/` - Modular PHP includes

### Custom Fields (ACF)
The theme includes pre-configured ACF field groups:
- Brand Assets
- Hero Section
- Page Builder Components

### Customizer Options
Access via WordPress Admin → Appearance → Customize:
- Brand Colors
- Typography Settings
- Animation Controls

## SCSS Architecture

### File Organization
```scss
// Import order (important!)
@import 'base/variables';    // First - contains mixins
@import 'base/reset';
@import 'base/typography';
@import 'layout/grid';
@import 'components/buttons';
// etc...
```

### Custom Properties
CSS custom properties are defined in `:root` for runtime customization:
```scss
:root {
  --skyworks-primary: #00ff88;
  --skyworks-secondary: #ff0080;
  --skyworks-space-md: 1rem;
}
```

### Mixins
Key mixins available:
- `@include neon-glow($color)` - Neon text effect
- `@include neon-box($color)` - Neon border effect
- `@include glass-morphism()` - Glass background
- `@include cyberpunk-border()` - Animated border

## JavaScript Development

### Main Scripts
- `main.js` - Core functionality
- `animations.js` - GSAP animations

### Key Classes
- `SkyWorks` - Main app controller
- `SkyWorksAnimations` - Animation controller

### Usage
```javascript
// Initialize animations
const animations = new SkyWorksAnimations();

// Add custom animation
animations.createScrollAnimation(element, {
  from: { opacity: 0, y: 50 },
  to: { opacity: 1, y: 0 }
});
```

## Component Development

### Creating New Components

1. **SCSS Component**
```scss
// components/_new-component.scss
.new-component {
  // Base styles
  background: var(--skyworks-dark);
  
  // Variants
  &--variant {
    // Variant styles
  }
  
  // States
  &:hover {
    // Hover styles
  }
}
```

2. **Add to main.scss**
```scss
@import 'components/new-component';
```

3. **HTML Usage**
```html
<div class="new-component new-component--variant">
  Content
</div>
```

### Best Practices
- Use BEM naming convention
- Include hover and focus states
- Ensure mobile responsiveness
- Test with screen readers
- Follow brand guidelines

## Performance Optimization

### CSS
- Critical CSS is inlined in `<head>`
- Non-critical CSS is loaded asynchronously
- Unused CSS is purged in production

### JavaScript
- Code splitting by functionality
- Lazy loading for animations
- Tree shaking removes unused code

### Images
- WebP format when supported
- Lazy loading for below-fold images
- Responsive images with srcset

## Testing

### Browser Testing
Ensure compatibility with:
- Chrome 90+
- Firefox 90+
- Safari 14+
- Edge 90+

### Accessibility Testing
- Screen reader compatibility
- Keyboard navigation
- Color contrast ratios
- Focus indicators

### Performance Testing
- Lighthouse scores 90+
- Core Web Vitals compliance
- Mobile-first optimization

## Deployment

### WordPress Theme
1. Copy `wp-theme/` to your WordPress themes directory
2. Activate in WordPress admin
3. Configure customizer settings
4. Install recommended plugins

### Static Files
1. Build assets: `npm run build`
2. Upload `dist/` files to your server
3. Update file paths in templates

## Headless Implementation

### Next.js Setup
```bash
cd headless/nextjs
npm install
npm run dev
```

### Astro Setup
```bash
cd headless/astro
npm install
npm run dev
```

## Common Issues

### Build Errors
- Check SCSS import order
- Ensure all mixins are defined before use
- Verify file paths in webpack config

### Animation Issues
- Check GSAP is loaded
- Verify element selectors exist
- Test with `prefers-reduced-motion`

### Style Issues
- Check specificity conflicts
- Verify CSS custom properties
- Test responsive breakpoints

## Contributing

1. Fork the repository
2. Create feature branch
3. Follow coding standards
4. Test thoroughly
5. Submit pull request

## Resources

- [GSAP Documentation](https://greensock.com/docs/)
- [WordPress Codex](https://codex.wordpress.org/)
- [SCSS Documentation](https://sass-lang.com/documentation)
- [Accessibility Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)