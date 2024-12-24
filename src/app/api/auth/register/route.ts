import { NextResponse } from 'next/server';
import type { RegisterData, AuthResponse } from '@/types/auth';

export async function POST(request: Request) {
  try {
    const body: RegisterData = await request.json();
    
    // Mock successful registration response
    const response: AuthResponse = {
      token: 'mock_jwt_token',
      user: {
        id: '1',
        name: body.name,
        email: body.email,
        role: body.role
      }
    };
    
    return NextResponse.json(response);
  } catch (error) {
    return NextResponse.json(
      { error: 'Registration failed' },
      { status: 400 }
    );
  }
}
