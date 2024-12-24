import { NextResponse } from 'next/server'
import type { NextRequest } from 'next/server'
 
// List of supported locales
const locales = ['en', 'id']
 
// Get the preferred locale from request headers
function getLocale(request: NextRequest) {
  const acceptLanguage = request.headers.get('accept-language')
  return acceptLanguage?.split(',')[0].split('-')[0] || 'en'
}
 
export function middleware(request: NextRequest) {
  const pathname = request.nextUrl.pathname
 
  // Check if the pathname is missing a locale
  const pathnameIsMissingLocale = locales.every(
    (locale) => !pathname.startsWith(`/${locale}/`) && pathname !== `/${locale}`
  )
 
  // If pathname is missing locale, redirect to the preferred locale
  if (pathnameIsMissingLocale) {
    const locale = getLocale(request)
    
    // Use supported locale or fallback to 'en'
    const finalLocale = locales.includes(locale) ? locale : 'en'
    
    return NextResponse.redirect(
      new URL(`/${finalLocale}${pathname}`, request.url)
    )
  }
}
 
export const config = {
  // Match all pathnames except those starting with:
  // - api (API routes)
  // - _next/static (static files)
  // - _next/image (image optimization files)
  // - favicon.ico (favicon file)
  matcher: ['/((?!api|_next/static|_next/image|favicon.ico).*)']
}
