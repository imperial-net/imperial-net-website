# Imperial-Net - Sitio Web Corporativo

Sitio web oficial de Imperial-Net - Soluciones de Software a Medida para PyMEs. Plataforma moderna desarrollada con Astro que ofrece información sobre nuestros servicios, casos de éxito, testimonios y blog.

## 🚀 Tecnologías Utilizadas

- **[Astro 5.12.3](https://astro.build)** - Framework web moderno y ultrarrápido
- **[Bootstrap 5.3.7](https://getbootstrap.com)** - Framework CSS para diseño responsive
- **[AOS 2.3.4](https://michalsnik.github.io/aos/)** - Animate On Scroll library
- **[Bootstrap Icons](https://icons.getbootstrap.com)** - Biblioteca de iconos
- **[Sharp](https://sharp.pixelplumbing.com/)** - Procesamiento de imágenes de alto rendimiento
- **[PHPMailer](https://github.com/PHPMailer/PHPMailer)** - Servicio de envío de emails SMTP

## 📁 Estructura del Proyecto

```
imperial-net-website/
├── public/                      # Archivos estáticos
│   ├── css/                    # Estilos CSS modulares
│   │   ├── navbar.css          # Estilos del navbar
│   │   ├── hero-section.css    # Estilos del hero
│   │   ├── solutions-section.css
│   │   ├── about-section.css
│   │   ├── cases-section.css
│   │   └── testimonials-section.css
│   ├── img/                    # Imágenes optimizadas
│   │   ├── logo.webp           # Logo principal (16KB)
│   │   └── logos/              # Logos de clientes (WebP)
│   ├── videos/                 # Videos
│   │   └── hero (2).mp4        # Video del hero (2MB)
│   ├── robots.txt              # Configuración para crawlers
│   ├── sitemap.xml             # Mapa del sitio
│   ├── site.webmanifest        # Manifest PWA
│   └── favicon.ico             # Favicons optimizados
├── src/
│   ├── components/             # Componentes Astro
│   │   ├── Navbar.astro        # Navegación principal
│   │   ├── HeroSection.astro   # Sección hero
│   │   ├── SolutionsSection.astro
│   │   ├── AboutSection.astro
│   │   ├── CasesSection.astro  # Casos de éxito
│   │   ├── Testimonials.astro  # Testimonios
│   │   ├── BlogSection.astro
│   │   ├── FAQ.astro
│   │   ├── ContactSection.astro
│   │   ├── CTASection.astro
│   │   ├── Footer.astro
│   │   └── WhatsAppButton.astro
│   ├── layouts/
│   │   └── BaseLayout.astro    # Layout base con SEO
│   ├── pages/
│   │   ├── index.astro         # Página principal
│   │   └── blog/               # Artículos del blog
│   │       ├── index.astro
│   │       ├── automatizacion-para-pymes.astro
│   │       ├── como-elegir-un-software-a-medida.astro
│   │       └── por-que-digitalizar-tu-empresa.astro
│   └── styles/
│       ├── global.css          # Estilos globales (imports)
│       └── base.css            # Estilos base del sitio
└── package.json

```

## 🎨 Características Principales

### 1. **Diseño Moderno y Responsive**
- Diseño mobile-first con Bootstrap 5
- Animaciones suaves con AOS
- Efectos visuales modernos (glassmorphism, gradientes)
- Navegación sticky con indicador de sección activa

### 2. **Secciones del Sitio**
- **Hero**: Video de fondo con CTA principal
- **Soluciones**: Grid de servicios ofrecidos
- **Nosotros**: Información corporativa con stats
- **Casos de Éxito**: Portfolio de clientes
- **Testimonios**: Opiniones de clientes reales
- **Blog**: Artículos sobre digitalización
- **FAQ**: Preguntas frecuentes
- **Contacto**: Formulario y datos de contacto
- **CTA**: Llamadas a la acción estratégicas

### 3. **Optimizaciones de Performance**

#### Imágenes
- ✅ Formato WebP (61% reducción de peso)
- ✅ Lazy loading para imágenes off-screen
- ✅ Dimensiones width/height especificadas
- ✅ Alt tags descriptivos para SEO

#### CSS
- ✅ CSS modular por componente
- ✅ Carga asíncrona de CSS no crítico
- ✅ Preconnect a CDNs externos
- ✅ ~2400 líneas organizadas en 7 archivos

#### JavaScript
- ✅ Scripts con defer
- ✅ Lazy loading de bibliotecas
- ✅ Event listeners optimizados

### 4. **SEO Optimizado**

#### Meta Tags
- ✅ Title y descriptions únicos
- ✅ Open Graph para redes sociales
- ✅ Twitter Cards
- ✅ Canonical URLs dinámicos
- ✅ Meta keywords relevantes

#### Structured Data
- ✅ Organization schema (JSON-LD)
- ✅ Logo y datos corporativos
- ✅ Redes sociales vinculadas

#### Indexación
- ✅ robots.txt configurado
- ✅ sitemap.xml completo
- ✅ Meta robots: index, follow
- ✅ Alt tags en todas las imágenes

### 5. **Accesibilidad**
- ✅ ARIA labels en elementos interactivos
- ✅ Navegación por teclado
- ✅ Contraste de colores adecuado
- ✅ Texto alternativo descriptivo
- ✅ HTML semántico

## 🛠️ Instalación y Desarrollo

### Requisitos Previos
- Node.js 18+
- npm o yarn

### Instalación

```bash
# Clonar el repositorio
git clone https://github.com/tu-usuario/imperial-net-website.git

# Entrar al directorio
cd imperial-net-website

# Instalar dependencias
npm install
```

### Comandos Disponibles

```bash
# Iniciar servidor de desarrollo
npm run dev
# Servidor disponible en http://localhost:4321

# Compilar para producción
npm run build

# Previsualizar build de producción
npm run preview

# Ejecutar Astro CLI
npm run astro
```

##  SEO y Metadatos

### URLs del Sitio
- Principal: `https://imperial-net.com`
- Blog: `https://imperial-net.com/blog`
- Artículos: `https://imperial-net.com/blog/[slug]`

### Sitemap Structure
```xml
Priority 1.0: Página principal
Priority 0.9: Soluciones, Blog
Priority 0.8: Nosotros, Casos
Priority 0.7: Artículos, Contacto
```


## 🎯 Casos de Éxito Destacados

1. **El Ceibo** - Club Deportivo
2. **Coblan** - Servicios Empresariales
3. **Sol Fotofashion** - Estudio Fotográfico
4. **Tienda en Sueños** - E-commerce
5. **Mi Chofer** - Marketing Digital
6. **VisualReport** - IA y Tecnología

## 📱 Componentes Principales

### Navbar
- Navegación sticky con efecto scroll
- Indicador de sección activa
- Menú responsive (hamburger en móvil)
- Logo optimizado en WebP

### Hero Section
- Video de fondo con overlay
- CTAs principales
- Badges informativos
- Animaciones de entrada

### Solutions Section
- Grid responsive de servicios
- Cards con hover effects
- Iconos descriptivos
- CTA de conversión

## 🚀 Deployment

El sitio está optimizado para deployment en:
- **Netlify** (recomendado)
- **Vercel**
- **GitHub Pages**
- **Cloudflare Pages**

### Build de Producción

```bash
npm run build
# Output en: dist/
```

## � Servicio de Email

El sitio incluye un servicio de email PHP con PHPMailer para el formulario de contacto.

### Estructura del Servicio

```
src/services/
├── api/
│   └── _send-email.php      # Endpoint API
├── email/
│   ├── EmailService.php     # Clase principal
│   ├── PHPMailer/           # Librería PHPMailer
│   └── templates/           # Templates HTML de emails
│       ├── consultation-email.html
│       └── confirmation-email.html
└── php/
    ├── config/
    │   └── mail.php         # Configuración
    └── utils/
        └── helpers.php      # Funciones auxiliares
```

### Configuración

Crear un archivo `.env` en la raíz del proyecto:

```env
SMTP_HOST=smtp.tuservidor.com
SMTP_PORT=465
SMTP_SECURE=true
SMTP_USER=tu-email@dominio.com
SMTP_PASS=tu-password
RECIPIENT_EMAIL=destinatario@dominio.com
```

### Deployment del Servicio de Email

Para producción, copiar la carpeta `src/services` a `public/services` o configurar el servidor PHP correspondientemente.


## 📄 Licencia

© 2025 Imperial-Net. Todos los derechos reservados.

## 👥 Contacto

- **Email**: info@imperial-net.com
- **WhatsApp**: +54 9 2392 55-6779
- **Web**: [https://imperial-net.com](https://imperial-net.com)
- **Ubicación**: Argentina

---

**Desarrollado con** ❤️ **por Imperial-Net**
