import { NextRequest, NextResponse } from 'next/server';
import type { LoginCredentials, AuthResponse } from '@/types/auth';

export async function POST(request: NextRequest) {
  try {
    const body: LoginCredentials = await request.json();
    
    // TODO: Implement actual authentication logic
    // For now, return a mock response
    return NextResponse.json({
      user: {
        id: '1',
        name: 'Test User',
        email: body.email,
        role: 'HOTEL_OWNER',
        createdAt: new Date(),
        updatedAt: new Date(),
      },
      token: 'mock_token'
    } as AuthResponse);
  } catch (error) {
    return NextResponse.json(
      { error: 'Invalid credentials' },
      { status: 401 }
    );
  }
}
