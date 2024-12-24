import { NextResponse } from 'next/server';
import type { ForgotPasswordData } from '@/types/auth';

export async function POST(request: Request) {
  try {
    const body: ForgotPasswordData = await request.json();
    
    // Mock successful password reset email sent
    return NextResponse.json({
      message: 'Password reset instructions sent to your email'
    });
  } catch (error) {
    return NextResponse.json(
      { error: 'Failed to process password reset request' },
      { status: 400 }
    );
  }
}
