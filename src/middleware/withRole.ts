import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';
import type { UserRole } from '../types/role';

export function withRole(role: UserRole) {
  return async function middleware(request: NextRequest) {
    // Get the user's role from the session/token
    // This is a placeholder - implement actual role checking logic
    const userRole = request.headers.get('x-user-role');

    if (!userRole || userRole !== role) {
      return NextResponse.json(
        { error: 'Unauthorized - Insufficient permissions' },
        { status: 403 }
      );
    }

    return NextResponse.next();
  };
}

// Example usage in API routes:
// export const config = {
//   matcher: '/api/hotels/:path*'
// };
// 
// export default withRole(UserRole.HOTEL_OWNER);
