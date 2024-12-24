import { NextResponse } from 'next/server';
import type { DashboardMetrics } from '../../../../types/dashboard';

export async function GET() {
  try {
    // Mock data - to be replaced with actual database queries
    const metrics: DashboardMetrics = {
      occupancy: 75,
      revenue: 15000,
      totalBookings: 120,
    };

    return NextResponse.json(metrics);
  } catch (error) {
    return NextResponse.json(
      { error: 'Failed to fetch dashboard metrics' },
      { status: 500 }
    );
  }
}
