@extends('layout.app')

@section('content')
<div class="billing-page">
    <div class="billing-header">
        <div class="title-group">
            <h1>Billing</h1>
            <p class="subtitle">Track payments and manage billing</p>
        </div>
    </div>

    <div class="billing-stats-row">
    <a href="{{ route('billing.index') }}" class="card-link">
        <div class="card-billing total">
            <div class="card-icon bg-green">
                <img src="{{ asset('images/icons/coin.png') }}" alt="Revenue">
            </div>
            <div class="card-content-right">
                <p>Total Revenue</p>
                <h3>₱{{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>
    </a>

  <a href="{{ route('billing.index', ['status' => 'refunds']) }}" class="card-link">
    <div class="card-billing refunds">
        <div class="card-icon bg-yellow">
            <img src="{{ asset('images/icons/cashback.png') }}" alt="Refunds">
        </div>
        <div class="card-content-right">
            <p>Refunds</p>
            <h3>{{ $pendingRefundsCount }}</h3>
        </div>
    </div>
</a>

    <a href="{{ route('billing.index', ['status' => 'completed', 'filter' => 'today']) }}" class="card-link">
        <div class="card-billing completed">
            <div class="card-icon bg-blue">
                <img src="{{ asset('images/icons/growth.png') }}" alt="Growth">
            </div>
            <div class="card-content-right">
                <p>Completed Today</p>
                <h3>₱{{ number_format($completedToday, 2) }}</h3>
            </div>
        </div>
    </a>

    <a href="{{ route('billing.index', ['status' => 'failed']) }}" class="card-link">
            <div class="card-billing failed">
                <div class="card-icon bg-red">
                    <img src="{{ asset('images/icons/payment.png') }}" alt="Failed">
                </div>
                <div class="card-content-right">
                    <p>Failed Payments</p>
                    {{-- This variable now correctly reflects the table data --}}
                    <h3>{{ $failedCount }}</h3>
                </div>
            </div>
        </a>

</div>

 <div class="billing-middle-row">
        <div class="upcoming-card">
    <h4>Upcoming Payments</h4>

    <div class="upcoming-list">
    @forelse($upcomingPayments as $upcoming)
        <div class="payment-card">
            <div class="left">
                <strong>{{ $upcoming->first_name }} {{ $upcoming->last_name }}</strong>
                <p>{{ $upcoming->membershipType->name ?? 'No Plan' }}</p>
                <small>Due: {{ \Carbon\Carbon::parse($upcoming->expiry_date)->format('M d, Y') }}</small>
            </div>
            <div class="right">₱{{ number_format($upcoming->membershipType->price ?? 0, 2) }}</div>
        </div>
    @empty
        <p style="color: #666; font-size: 0.85rem; text-align: center; padding: 20px 0; font-weight: 700;">
            No upcoming payments in the next 7 days.
        </p>
    @endforelse
</div>
</div>

        <div class="quick-container">
  <h2>Quick Actions</h2>

  <div class="quick-actions">
    <button class="quick-card" onclick="openModal('paymentModal')">
      <img src="{{ asset('images/icons/wallet.png') }}" style="width: 30px; height: 30px;">
      <p>Renew Membership</p>
    </button>

    <button class="quick-card" onclick="openModal('refundModal')">
      <img src="{{ asset('images/icons/cashback.png') }}" style="width: 30px; height: 30px;">
      <p>Refund</p>
    </button>
  </div>
</div>



<!-- PAYMENT MODAL -->
<div id="paymentModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Process Payment / Renewal</h3>
        </div>

        <form id="paymentForm" method="POST">
            @csrf
            <div class="form-grid">

                {{-- Hidden fields --}}
                <input type="hidden" name="member_id" id="payment_member_id">
                <input type="hidden" name="amount" id="payment_amount_hidden">

                {{-- Member Search --}}
                <div class="form-group">
                    <label>Member Name</label>
                    <input
                        type="text"
                        id="payment_member_search"
                        list="member_list"
                        placeholder="Search for a member..."
                        autocomplete="off"
                        style="width: 100%;"
                    >
                    <datalist id="member_list">
                        @foreach($membersForRenewal as $m)
                            <option
                                value="{{ $m->first_name }} {{ $m->last_name }}"
                                data-id="{{ $m->member_id }}"
                                data-price="{{ $m->membershipType->price ?? 0 }}"
                                data-plan-id="{{ $m->membership_type_id }}"> {{-- ADD THIS LINE --}}
                            </option>
                        @endforeach
                    </datalist>
                </div>

                {{-- Membership Plan --}}
                <div class="form-group">
                    <label>Membership Plan</label>
                    <select name="membership_type_id" id="plan_selector" class="form-control" onchange="updateHiddenPrice(this)">
                        @foreach($membershipTypes as $type)
                            <option value="{{ $type->id }}" data-price="{{ $type->price }}">
                                {{ $type->name }} — ₱{{ number_format($type->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Payment Method --}}
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" required>
                        <option value="Cash">Cash</option>
                        <option value="GCash">GCash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEquipmentModal('paymentModal')">Cancel</button>
                <button type="submit" id="submitPaymentBtn" class="btn-register" disabled>Confirm & Renew</button>
            </div>
        </form>
    </div>
</div>

<!-- REFUND MODAL -->
<div id="refundModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Process Refund Disbursement</h3>
        </div>

        <form id="refundForm" action="{{ route('refunds.disburse') }}" method="POST">
            @csrf
            <div class="form-grid">
                <input type="hidden" name="member_id" id="refund_member_id">

                {{-- FIELD 1: Member Search --}}
                <div class="form-group">
                    <label>Member Name</label>
                    <input type="text" id="refund_member_search" list="refund_eligible_list" 
                           class="form-control" placeholder="Search eligible member..." autocomplete="off">
                    <datalist id="refund_eligible_list">
                        @foreach($eligibleForRefund as $m)
                            <option value="{{ $m->first_name }} {{ $m->last_name }}" 
                                    data-id="{{ $m->member_id }}" 
                                    data-plan-name="{{ $m->membershipType->name ?? 'No Plan' }}">
                            </option>
                        @endforeach
                    </datalist>
                </div>

                {{-- FIELD 2: Previous Subscription (Read-only) --}}
                <div class="form-group">
                    <label>Previous Subscription</label>
                    <input type="text" id="refund_plan_display" name="membership_type"
                           class="form-control" readonly 
                           placeholder="Select member first..." 
                           style="background-color: rgba(255,255,255,0.05); color: #ccc;">
                </div>

                {{-- FIELD 3: Disbursement Status --}}
                <div class="form-group">
                    <label>Refund Disbursement Status</label>
                    <select name="disbursement_status" id="refund_action" class="form-control">
                        <option value="disbursed">Refund Disbursed</option>
                        <option value="pending_disbursement">Awaiting Disbursement</option>
                    </select>
                </div>

                {{-- FIELD 4: Date Received --}}
                <div class="form-group" id="date_received_group">
                    <label>Date Member Received Refund</label>
                    <input type="date" name="disbursement_date" id="disbursement_date" 
                           class="form-control" value="{{ date('Y-m-d') }}">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('refundModal')">Cancel</button>
                <button type="submit" id="submitRefundBtn" class="btn-register" disabled>
                    Confirm Disbursement
                </button>
            </div>
        </form>
    </div>
</div>

{{-- REFUND VIEW MODAL --}}
<div id="viewRefundModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Refund Details</h3>
            <button type="button" class="close-btn" onclick="closeModal('viewRefundModal')">&times;</button>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Member Name</label>
                <input type="text" id="view_member_name" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Membership Plan</label>
                <input type="text" id="view_membership_type" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Reason</label>
                <input type="text" id="view_reason" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Status</label>
                <input type="text" id="view_status" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Disbursement Status</label>
                <input type="text" id="view_disbursement_status" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Date Received</label>
                <input type="text" id="view_disbursement_date" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Date Requested</label>
                <input type="text" id="view_created_at" class="form-control" readonly>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal('viewRefundModal')">Close</button>
        </div>
    </div>
</div>

    </div>

    <div class="billing-action-wrapper">
    <h2 class="history-label">Activity History</h2>

    <div class="billing-gold-bar">
        <form action="{{ route('billing.index') }}" method="GET" class="billing-search-form">
            
            {{-- Preserve current table context --}}
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif

            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="{{ request('status') === 'refunds' ? 'Search refunds...' : (request('status') === 'failed' ? 'Search failed...' : 'Search transactions...') }}"
                   onchange="this.form.submit()">
        </form>
        
        <button class="billing-gold-filter">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
    </div>
</div>

   <div class="table-wrapper">
            <div class="billing-grid-row {{ $isRefundTable ? 'refund-grid' : ($isFailedTable ? 'failed-grid' : 'regular-grid') }}">
                @if($isRefundTable)
                    <div>Member</div>
                    <div>Membership Plan</div>
                    <div>Refund Reason</div>
                    <div>Date & Time</div>
                    <div>Status</div>
                    <div style="text-align: center;">Action</div>
                @elseif($isFailedTable)
                        <div>Member ID</div>
                        <div>Member Name</div>
                        <div>Plan</div>
                        <div>Amount</div>
                        <div>Expiry Date</div>
                        <div>Status</div>
                    @else
                    <div>Transaction ID</div>
                    <div>Member</div>
                    <div>Type</div>
                    <div>Amount</div>
                    <div>Method</div>
                    <div>Date & Time</div>
                    <div>Status</div>
                @endif
            </div>
            
    @foreach($payments as $item)
       <div class="billing-grid-row {{ $isRefundTable ? 'refund-grid' : ($isFailedTable ? 'failed-grid' : 'regular-grid') }} {{ $isRefundTable && $item->status == 'Pending' ? 'pending-highlight' : '' }}">
                    @if($isRefundTable)
                        <div>{{ $item->member->first_name ?? 'N/A' }} {{ $item->member->last_name ?? '' }}</div>
                        <div>{{ $item->membership_type ?? ($item->membershipType->name ?? 'N/A') }}</div>
                        <div class="reason-box">
                            <p class="reason-text">"{{ $item->reason }}"</p>
                        </div>
                        <div>{{ $item->created_at->format('M d, Y h:i A') }}</div>
                        
                        <div>
                            <span class="status-pill {{ strtolower($item->status) }}">
                                {{ $item->status }}
                            </span>
                        </div>

                        <div class="action-cell">
                                @if($item->status == 'Pending')
                                    @if(auth()->guard('admin')->user()->role === 'super_admin' || auth()->guard('admin')->user()->role === 'admin')
                                        <div class="approval-actions">
                                            <form action="{{ route('refunds.approve', $item->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="approve-icon-btn" title="Approve Refund">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('refunds.decline', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Decline this refund request?')">
                                                @csrf
                                                <button type="submit" class="decline-icon-btn" title="Decline Request">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span style="color: #555; font-size: 0.8rem;">
                                            <i class="fa-solid fa-clock"></i> Pending
                                        </span>
                                    @endif
                                @else
                                    <button class="edit-btn" title="View Details"
                                        onclick="viewRefundDetails(
                                            '{{ $item->member->first_name ?? 'N/A' }} {{ $item->member->last_name ?? '' }}',
                                            '{{ $item->membership_type ?? ($item->membershipType->name ?? 'N/A') }}',
                                            '{{ addslashes($item->reason) }}',
                                            '{{ $item->status }}',
                                            '{{ $item->disbursement_status ?? 'N/A' }}',
                                            '{{ $item->disbursement_date ?? 'Not yet received' }}',
                                            '{{ $item->created_at->format('M d, Y h:i A') }}'
                                        )">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    @endif
                                    
                    </div>
                    @elseif($isFailedTable)
                            <div>#{{ $item->member_id }}</div>
                            <div>{{ $item->first_name }} {{ $item->last_name }}</div>
                            <div>{{ $item->membershipType->name ?? 'N/A' }}</div>
                            <div>₱{{ number_format($item->membershipType->price ?? 0, 2) }}</div>
                            <div>{{ \Carbon\Carbon::parse($item->expiry_date)->format('M d, Y') }}</div>
                            <div><span class="status-pill failed">Expired</span></div>
                    @else
                        <div>#{{ $item->transaction_id ?? 'TRX-' . $item->id }}</div>
                        <div>{{ $item->member->first_name ?? 'Guest' }} {{ $item->member->last_name ?? '' }}</div>
                        <div>{{ ucfirst($item->type) }}</div>
                        <div>₱{{ number_format($item->amount, 2) }}</div>
                        <div>{{ strtoupper($item->payment_method ?? 'N/A') }}</div>
                        <div>{{ $item->created_at->format('M d, Y h:i A') }}</div>
                        <div>
                            <span class="status-pill {{ $item->status == 'completed' ? 'good' : 'amber' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                    @endif
                </div>
    @endforeach
</div>

            <div class="pagination-container">
                @if($payments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $payments->links() }}
                @endif
            </div>



</div>
@endsection


@push('scripts')
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('js/members.js') }}"></script>
    <script src="{{ asset('js/billing.js') }}"></script>
    <script src="{{ asset('js/dropdown.js') }}"></script>
@endpush