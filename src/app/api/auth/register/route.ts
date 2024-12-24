import { NextRequest, NextResponse } from 'next/server';
import type { RegisterData, AuthResponse } from '@/types/auth';

export async function POST(request: NextRequest) {
  try {
    const body: RegisterData = await request.json();
    
    // TODO: Implement actual registration logic
    // For now, return a mock response
    return NextResponse.json({
      user: {
        id: '1',
        name: body.name,
        email: body.email,
        role: body.role,
        createdAt: new Date(),
        updatedAt: new Date(),
      },
      token: 'mock_token'
    } as AuthResponse);
  } catch (error) {
    return NextResponse.json(
      { error: 'Registration failed' },
      { status: 400 }
    );
  }
}
