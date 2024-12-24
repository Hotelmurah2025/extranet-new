import { NextResponse } from 'next/server';
import type { LoginCredentials, AuthResponse } from '@/types/auth';

export async function POST(request: Request) {
  try {
    const body: LoginCredentials = await request.json();
    
    // Mock successful login response
    const response: AuthResponse = {
      token: 'mock_jwt_token',
      user: {
        id: '1',
        name: 'John Doe',
        email: body.email,
        role: 'HOTEL_OWNER'
      }
    };
    
    return NextResponse.json(response);
  } catch (error) {
    return NextResponse.json(
      { error: 'Invalid credentials' },
      { status: 401 }
    );
  }
}
