import { NextResponse } from 'next/server';
import { UserRole } from '../../../types/role';
import type { Role, Permission } from '../../../types/role';

// Mock data store - replace with actual database in production
let roles: Role[] = [
  {
    id: '1',
    name: UserRole.HOTEL_OWNER,
    permissions: [
      {
        id: 'manage_hotels',
        name: 'Manage Hotels',
        description: 'Create, edit, and delete assigned hotels',
        scope: 'hotel'
      },
      {
        id: 'manage_rooms',
        name: 'Manage Rooms',
        description: 'Manage room types and their details',
        scope: 'hotel'
      },
      {
        id: 'manage_rates',
        name: 'Manage Rates',
        description: 'Manage rate plans and pricing',
        scope: 'hotel'
      }
    ]
  },
  {
    id: '2',
    name: UserRole.SUPER_ADMIN,
    permissions: [
      {
        id: 'manage_all_hotels',
        name: 'Manage All Hotels',
        description: 'Full control over all hotels in the system',
        scope: 'system'
      },
      {
        id: 'manage_users',
        name: 'Manage Users',
        description: 'Manage all users and their roles',
        scope: 'system'
      },
      {
        id: 'manage_system',
        name: 'Manage System',
        description: 'Configure system-wide settings',
        scope: 'system'
      }
    ]
  }
];

// GET /api/roles - Get all roles
export async function GET() {
  try {
    return NextResponse.json(roles);
  } catch (error) {
    return NextResponse.json(
      { error: 'Failed to fetch roles' },
      { status: 500 }
    );
  }
}

// POST /api/roles - Create a new role
export async function POST(request: Request) {
  try {
    const body = await request.json();
    const { name, permissions } = body;

    // Validate role data
    if (!name || !permissions || !Array.isArray(permissions)) {
      return NextResponse.json(
        { error: 'Invalid role data' },
        { status: 400 }
      );
    }

    const newRole: Role = {
      id: Date.now().toString(),
      name,
      permissions
    };

    roles.push(newRole);
    return NextResponse.json(newRole, { status: 201 });
  } catch (error) {
    return NextResponse.json(
      { error: 'Failed to create role' },
      { status: 500 }
    );
  }
}
